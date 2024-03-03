from WebsiteObject import Website
import sqlite3

def openCrawlList():

    connection = sqlite3.connect('C:\\Users\\Jonah\\Documents\\Third Year\\Big Project\\Databases\\webpages.db')
    cursor = connection.cursor()

    cursor.execute("SELECT * FROM webpages")

    rows = cursor.fetchall()
    websiteList = []

    # Process the data
    for row in rows:
        # Turn each row into a new website
        newWebsite = Website(row[0], row[1], row[2], row[3], row[4], row[5])
        websiteList.append(newWebsite)

    cursor.close()
    connection.close()

    return websiteList

if __name__ == "__main__":
    openCrawlList()