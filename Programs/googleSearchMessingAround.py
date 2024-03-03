
# Import the beautifulsoup  
# and request libraries of python. 
  
from bs4 import BeautifulSoup
import requests

def one():

    url = 'https://en.wikipedia.org/wiki/Snake'  # Replace with the URL of the webpage you want to scrape
    response = requests.get(url)

    soup = BeautifulSoup(response.text, 'lxml')
    title_element = soup.find('title')

    if title_element is not None:
        title = title_element.text
        print("Title of the webpage:", title)
    else:
        print("Title not found on the webpage.")

    # Find the first three paragraph elements
    paras = soup.find_all(['p'])

    if paras:
        for index, heading in enumerate(paras[:3]):
            heading_text = heading.text
            print(f"Text from heading {index + 1}: {heading_text}")
    else:
        print("No headings found on the webpage.")

    # Find the first three h2 heading elements
    headings = soup.find_all(['h2'])

    if headings:
        for index, heading in enumerate(headings[:3]):
            heading_text = heading.text
            print(f"Text from heading {index + 1}: {heading_text}")
    else:
        print("No headings found on the webpage.")


def two():

    search_query = str(input("Enter your search query:\n"))
    google_url = f"https://www.google.com/search?q={search_query}"

    response = requests.get(google_url)
    soup = BeautifulSoup(response.text, 'lxml')

    search_results = soup.find_all('div', class_='tF2Cxc')

    top_5_websites = []

    for result in search_results[:5]:
        title = result.h3.text
        url = result.a['href']
        top_5_websites.append({'title': title, 'url': url})

    # Print the top 5 websites
    for index, website in enumerate(top_5_websites, start=1):
        print(f"{index}. Title: {website['title']}")
        print(f"   URL: {website['url']}\n")


two()
   