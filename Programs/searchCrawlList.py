from typing import Any
import binarySearch
from WebsiteObject import Website
import OpenCrawlList

def search(query):

    # Open the crawl list file to get all of the website objects
    websiteList = OpenCrawlList.openCrawlList()
    
    # Find the desired element in the crawl list using the binary search module
    # Search the keywords for a match with the search query??
    matches = binarySearch.search(websiteList, query)

    # Display the results to the user
    if len(matches) == 0:
        print("No matches")
    else:
        print("Here are the websites matching your search:")

        for website in matches:
            print(website.getTitle())

    return matches


if __name__ == "__main__":
    search("keyword8")