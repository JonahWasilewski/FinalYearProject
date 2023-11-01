import requests
from bs4 import BeautifulSoup
from urllib.parse import urlparse, urljoin
# Import the fake_useragent library
from fake_useragent import UserAgent
from itertools import combinations

from WebsiteSummariser import summarise

from WebsiteObject import Website

import time

# Function to fetch a web page with retries
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
        time.sleep(2)  # Wait for a short time before retrying
    return None

# Function to get search results links from Google
def get_google_search_results(query):
    base_url = "https://www.google.com/search"
    params = {"q": query}

    # Generate a random user agent string
    ua = UserAgent()
    user_agent = ua.random

    headers = {
        "User-Agent": user_agent
    }

    response = requests.get(base_url, params=params, headers=headers)

    if response.status_code == 200:
        soup = BeautifulSoup(response.text, "html.parser")
        search_results = soup.find_all("a")
        links = [a["href"] for a in search_results if a.get("href") and a["href"].startswith("http")]
        return links
    else:
        print("Failed to retrieve search results.")
        return []

# Main function to crawl a website
def web_crawler(seed_url, max_depth, max_pages, visited_urls=None):

    if visited_urls is None:
        visited_urls = []

    to_visit = [(seed_url, 0)]
    visited_titles = []
    visited_summaries = []
    newWesbiteList = []
    weird_title_counter = 0
    visited_pages = 0
    relevant_pages = []  # Store relevant pages

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

                if title == '':
                    continue

                try:
                    summary, keywordsFromWebsite = summarise(url)
                    visited_summaries.append(summary)
                    print("Summary:\n" + summary)
                except AttributeError:
                    print("This didn't work")
                    summary = " "

                newWesbite = Website(title, keywordsFromWebsite, url, summary)
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

    max_depth = 1  # Maximum depth of crawling
    max_pages = 2  # Maximum number of pages to crawl

    visited_titles = []
    visited_summaries = []
    visited_urls = []

    websiteList = []

    # Crawl each search result
    for result in search_results:
        newWebsitesList = web_crawler(result, max_depth, max_pages, visited_urls)
        websiteList.extend(newWebsitesList)

    print("Visited Titles:")
    for website in websiteList:
        print(website.getTitle())
        print("Visited URLs:")
        print(website.getUrl())

    saveToCrawlList(websiteList)

def saveToCrawlList(websiteList):

    # Specify the file name
    file_name = "C:\\Users\\Jonah\\Documents\\Third Year\\Big Project\\Web Stuff\\crawlList.txt"

    for i in range(len(websiteList)):

        # Also should check if the title is already in the list rathter than a complete match - repeated titles are bad
        
        crawlList = open(file_name, 'r', encoding='utf8', errors='ignore')

        # Create the new line to be added to the crawl list - in the correct format
        data_to_write = websiteList[i].getTitle() + ": " 
        for j in range(len(websiteList[i].getKeywords()) - 1):
            data_to_write += websiteList[i].getKeywords()[j] + ", "

        # If this line doesnt work then no keywords have been found in the website - ie it's not got much text. So dont add the website to the crawl list    
        try:
            data_to_write += websiteList[i].getKeywords()[-1]
        except:
            continue

        data_to_write += ": " + websiteList[i].getUrl() + ": " + websiteList[i].getSummary()

        # Check if the data is already in the file
        data_exists = False

        try:
            for line in crawlList:
                if data_to_write in line:
                    data_exists = True
                    break
        except FileNotFoundError:
            pass

        crawlList.close()
        crawlList = open(file_name, 'a', encoding="utf8", errors='ignore')

        # If the data doesn't exist in the file, append it
        if not data_exists:
            try:
                crawlList.write(data_to_write + "\n")
                print(f"Data added to {file_name}")
            except:
                print("Data couldnt be added to crawl list cause its weird")
        else:
            print("Data already exists in the file.")

        crawlList.close()

if __name__ == "__main__":
    query = "world cup"
    #seed_url = f"https://www.google.com/search?q={query}"
    initialise(query)
