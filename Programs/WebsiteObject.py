class Website:
    def __init__(self, title, keywords, url, summary, relevanceScore, pageRank):
        self.title = title
        self.keywords = keywords
        self.url = url
        self.summary = summary
        self.relevanceScore = relevanceScore
        self.pageRank = pageRank

    # Getters for website objects - doesnt need any setters as these cant be changed by any part of the program
    def getTitle(self):
        return self.title
    
    def getKeywords(self):
        return self.keywords
    
    def getUrl(self):
        return self.url

    def getSummary(self):
        return self.summary
    
    def getRelevanceScore(self):
        return self.relevanceScore
    
    def getPageRank(self):
        return self.pageRank