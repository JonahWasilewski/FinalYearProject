import math
from WebsiteObject import Website

def search(websiteList, query):

    # Create a 'query list' of the individual words in the user's search to match to any keywords stored for websites
    queryElements = query.split(" ")

    # Create a list of the websites that contain at least one of the keywords to return to the user after the search is complete
    matchedWebsiteList = []

    # Need to do a binary search of each website's keyword list individually to see whcih websites are linked to the search
    # Note, when the crawler saves the keywords to the crawl list, it will ensure than all keyword entries are only one word
    # This will save the need to add another for loop where i will have to check all words in a single keyword entry
    for currentWebsite in websiteList:

        keywords = currentWebsite.getKeywords()

        for element in queryElements:

            # Define pointers for the start and end of the list
            start = 0
            end = len(keywords) - 1
            found = False

            # Main loop
            while (start <= end) and not found:
                # Define middle pointer
                middle = math.floor((start + end) / 2)

                # Look for position of element in relation to middle
                if keywords[middle] == element:
                    found = True

                    # Add website to the list 
                    matchedWebsiteList.append(currentWebsite)

                    # Currenly, I'm just allowing the potential for the same website to be in the list multiple times
                    # It won't be shown to the user twice in the program but instead will be used to show that the website 
                    # contains more than one keyword from the search and hence is a better fit for the query than other websites

                else:
                    if keywords[middle] < element:
                        # Element is in the second half of the list
                        start = middle + 1
                    else:
                        # Element is in the first half of the list
                        end = middle - 1

    return matchedWebsiteList

if __name__ == "__main__":

    websiteList = []

    newWebsite1 = Website("Title1", ["keyword1", "keyword2", "keyword3"], "www.test.com")
    websiteList.append(newWebsite1)

    newWebsite2 = Website("Title2", ["keyword1", "keyword3", "keyword4"], "www.test2.com")
    websiteList.append(newWebsite2)

    newWebsite3 = Website("Title3", ["keyword1", "keyword5", "keyword6"], "www.tes3.com")
    websiteList.append(newWebsite3)

    newWebsite4 = Website("Title4", ["keyword2", "keyword3", "keyword6"], "www.test4.com")
    websiteList.append(newWebsite4)

    matches = search(websiteList, "keyword6 keyword1")

    if len(matches) == 0:
        print("No matches")
    else:
        print("Here are the websites matching your search:")

        for website in matches:
            print(website.getTitle())
