import requests
from bs4 import BeautifulSoup
from urllib.parse import urlparse, urljoin
from fake_useragent import UserAgent
from itertools import combinations
from WebsiteSummariser import summarise
from WebsiteObject import Website
from SaveToCrawlList import saveToCrawlList
import time
import networkx as nx

# Function to fetch a web page with retries
# Retires occur when the crawler tries to access a website but the delay is too long
# Aim is to ensure that the crawler doesnt get stuck on one webpage (for whatever reason) and will move on if needed
def fetch_page_with_retries(url, max_retries=3):
    retries = 0
    while retries < max_retries:
        try:
            response = requests.get(url, allow_redirects=True)
            if response.status_code == 200:
                return response.text
        except requests.exceptions.RequestException as e:
            print(f"Request error: {e}")
        retries += 1
        time.sleep(1)  # Wait for a short time before retrying
    return None

# Function to get search results links from Google
def get_google_search_results(query):
    base_url = "https://www.google.com/search"
    params = {"q": query}

    # Generate a random user agent string
    # We need a user agent because otherwise it moght appear to a website as a dodgy piece of software and wont allow access
    ua = UserAgent()
    user_agent = ua.random

    headers = {
        "User-Agent": user_agent
    }

    response = requests.get(base_url, params=params, headers=headers)

    if response.status_code == 200:
        # Parsers the webpage using the BeautifulSoup library
        # Gets tags from the webpage along with their associated data
        soup = BeautifulSoup(response.text, "html.parser")
        # Get all <a> tags - ie all links on the page
        search_results = soup.find_all("a")
        # Store these links as ling as they follow a specified format - ie http
        links = [a["href"] for a in search_results if a.get("href") and a["href"].startswith("http")]
        return links
    else:
        print("Failed to retrieve search results.")
        return []
    
# This is used to create a graph of the crawled websites to workout the number of links to and from each one
# Needed so that the pagerank of each website can be calculated to gice an idea of how rekevant the webpage is
def create_website_graph(websites, max_depth):
    # Initialise the directed graph
    G = nx.DiGraph()

    # Sets the nodes of the graph to all the websites visited
    for website in websites:
        G.add_node(website.getUrl())

    for website in websites:
        # Limit the depth of hyperlinks to avoid excessive crawling
        if website.getPageRank() > 0.2 and website.getRelevanceScore() >= 0.5:
            depth = 0
            to_visit = [(website.getUrl(), depth)]
            visited = set()

            while to_visit and depth < max_depth:
                url, depth = to_visit.pop(0)
                visited.add(url)
                links = [link.getUrl() for link in websites if link.getUrl() in website.getKeywords()]

                for link in links:
                    if link not in visited:
                        G.add_edge(website.getUrl(), link)
                        to_visit.append((link, depth + 1))

    return G


# Main function to crawl a website
def web_crawler(seed_url, max_depth, max_pages, visited_urls=None):

    if visited_urls is None:
        visited_urls = []

    # At first, the only webpage that we know about, and hence need to visit, is the one weve been given by the query
    to_visit = [(seed_url, 0)]
    visited_titles = []                     # Will store the webpages that we have been to and fully explored
    visited_summaries = []
    newWesbiteList = []                     # Will store the websites as website objects
    visited_pages = 0                       # Keep track of the number of visited webpages so we dont keep crawling forever
    relevant_pages = []                     # Store relevant pages

    # Split the query up into a list of words that can be checked in the text of a webpage
    keywords = query.split()

    # Common words should be removed as they have no impact on the relevancy of the webpage in respect to the search
    f = open("txtFiles/irrelevantWordList.txt", "r")
    irrelevant_words = f.read()

    while to_visit and visited_pages < max_pages:
        url, depth = to_visit.pop(0)

        if depth > max_depth:
            continue

        if url not in visited_urls:
            visited_urls.append(url)
            print(f"Crawling: {url}")

            page_content = fetch_page_with_retries(url, 3)

            if page_content:
                visited_pages += 1
                # Parse the HTML
                soup = BeautifulSoup(page_content, 'html.parser')

                # Extract data or follow links as needed
                try:
                    title = soup.find('h1').text
                    words = title.split()
                    title = ' '.join(words)
                    visited_titles.append(title)
                    print("Webpage title: " + title)
                except AttributeError:
                    continue

                if title == '' or title.lower() == 'no page information in search results' or title.lower == 'JavaScript is not available.':             # Titles that show the website is of no use to the user
                    continue

                try:
                    summary, keywordsFromWebsite = summarise(url)
                    visited_summaries.append(summary)
                    print("Summary:\n" + summary)
                except AttributeError:
                    print("This didn't work")
                    summary = " "

                newWesbite = Website(title, keywordsFromWebsite, url, summary, 0, 0.0)
                newWesbiteList.append(newWesbite)

                # Analyze content and calculate a relevance score
                relevance_score = calculate_relevance_score(title, summary, query, keywords, irrelevant_words)

                if relevance_score >= 0.5:  # Adjust the threshold as needed
                    relevant_pages.append((title, url, relevance_score))

                # Find and follow links on the page and add to the to_visit list
                links = soup.find_all('a', href=True)
                for link in links:
                    new_url = link['href']

                    if is_relative_url(new_url):
                        new_url = urljoin(seed_url, new_url)

                    to_visit.append((new_url, depth + 1))

    return newWesbiteList

def calculate_relevance_score(title, content, query, keywords, irrelevant_words):
    # You can use natural language processing or other methods to calculate relevance.
    # For simplicity, let's calculate a relevance score based on the presence of keywords. 

    total_count = 0   
    
    for r in range(1, len(keywords) + 1):
        for combo in combinations(keywords, r):
            sub_string = "".join(combo)
            count = content.count(sub_string)
            total_count += count

    # Calculate the relevance score based on keyword presence
    #keyword_count = sum([1 for word in tokenized_title + tokenized_content if word in keywords])
    relevance_score = total_count / (len(title) + len(content))

    return relevance_score

def is_relative_url(url):
    parsed_url = urlparse(url)
    return not all([parsed_url.scheme, parsed_url.netloc])

def initialise(query):
    search_results = get_google_search_results(query)

    max_depth = 2  # Maximum depth of crawling
    max_pages = 5  # Maximum number of pages to crawl

    visited_titles = []
    visited_summaries = []
    visited_urls = []
    websiteList = []

    # Crawl each search result
    for result in search_results:
        newWebsitesList = web_crawler(result, max_depth, max_pages, visited_urls)
        websiteList.extend(newWebsitesList)

    # Calculate PageRank
    graph = create_website_graph(websiteList, max_depth)
    pagerank = nx.pagerank(graph)

    # Update the PageRank attribute of Website objects
    for website in websiteList:
        website.pageRank = pagerank[website.getUrl()]

    print("Visited Titles:")
    for website in websiteList:
        print(website.getTitle())
        print("Visited URLs:")
        print(website.getUrl())

    saveToCrawlList(websiteList)

"""
def saveToCrawlList(websiteList):

    # Establish the connection with the database - located on the specified location 
    connection = sqlite3.connect('C:\\Users\\Jonah\\Documents\\Third Year\\Big Project\\Web Stuff\\webpages.db')
    # Initialise the cursor that will be used as a pointer as we go through the fields in the database
    cursor = connection.cursor()

    # Will store the newly found websites in the required format to be stored in the database.
    dataToWrite = []

    # Loop through the website list to turn each website object into a tuple
    for website in websiteList:

        # A database in 3NF can only have one piece of data per field so we need to create a new entry for eacb of the keywords in the keyword list
        for keyword in website.getKeywords():
            dataToWrite = (website.getTitle(), keyword, website.getUrl(), website.getSummary(), website.getRelevanceScore(), website.getPageRank())

            # Check if the record with the same URL and keyword already exists
            # If it already existst then we wont add the website to the database - or it will cause an error
            cursor.execute("SELECT * FROM webpages WHERE URL = ? AND keyword = ?", (website.getUrl(), keyword))
            existing_record = cursor.fetchone()

            if existing_record is None:
                # Insert the record if it doesn't exist
                cursor.execute("INSERT INTO webpages (title, keyword, URL, summary, relevanceScore, pageRank) VALUES (?, ?, ?, ?, ?, ?)", dataToWrite)
                connection.commit()

    # Close the connection and cursor as we're done using them
    cursor.close()
    connection.close()

"""


if __name__ == "__main__":
    queryList = ["banana"]

    for i in queryList:
        query = i
        initialise(query)
