from WebsiteObject import Website

def openCrawlList():
    
    # Open the crawl list
    f = open("txtFiles/crawlList.txt", "r")
    crawlList = f.read()

    # Split up the crawl list into separate lines - one object is on each line
    crawlList = crawlList.split("\n")

    websiteList = []

    # Create objects for each of the lines
    for i in range(len(crawlList)):
        lineList = crawlList[i].split(": ")
        # Assign each attribute of the website to a variable 
        title = lineList[0]
        keywords = lineList[1].split(", ")
        url = lineList[2]

        # Create the wesbite object from the extracted info
        newWebsite = Website(title, keywords, url)
        websiteList.append(newWebsite)

    return websiteList

if __name__ == "__main__":
    openCrawlList()
