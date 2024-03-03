import sqlite3
from WebsiteObject import Website

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

if __name__ == "__main__":
    
    websiteList = [Website("title", ["keyword1, keyword2"], "url", "summary", 0, 0.0)]
    saveToCrawlList(websiteList)