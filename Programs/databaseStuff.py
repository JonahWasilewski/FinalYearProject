from WebsiteObject import Website
import sqlite3

connection = sqlite3.connect('C:\\Users\\Jonah\\Documents\\Third Year\\Big Project\\Databases\\webpages.db')
cursor = connection.cursor()

cursor.execute("SELECT * FROM webpages")

rows = cursor.fetchall()

# Process the data
for row in rows:
    print(row)

    # Turn each row into a new website
    newWebsite = Website(row[0], row[1], row[2], row[3], row[4], row[5])
    print(newWebsite.getTitle())

cursor.close()


cursor = connection.cursor()
newWebsite = Website("title", ["keyword1", "keyword2"], "url", "summary", 0, 0.0)
dataToWrite = []

for i in newWebsite.getKeywords():
    dataToWrite.append((newWebsite.getTitle(), i, newWebsite.getUrl(), newWebsite.getSummary(), 0, 0.0))

# Perform the insert query for each of the website's keywords
for i in range(len(dataToWrite)):
    cursor.execute("INSERT INTO webpages (title, keyword, URL, summary, relevanceScore, pageRank) VALUES (?, ?, ?, ?, ?, ?)", dataToWrite[i])
    connection.commit()

cursor.close()
connection.close()
