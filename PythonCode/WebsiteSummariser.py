import requests
from bs4 import BeautifulSoup
from transformers import pipeline, AutoTokenizer, AutoModelForSeq2SeqLM

import spacy
from collections import Counter

from MergeSort import mergeSort

def summarise(url): 

    response = requests.get(url)
    response.close()
    soup = BeautifulSoup(response.text, 'html.parser')

    # Extract text data from the website
    text_data = ''
    for tag in soup.find_all(['p', 'h1', 'h2', 'h3', 'h4', 'h5', 'h6']):
        text_data += tag.get_text()

    if len(text_data) > 1024:
        text_data = text_data[:1024]

    # Load the summarization pipeline without specifying the revision
    summarizer = pipeline("summarization", model="facebook/bart-large-cnn")
    summary = summarizer(text_data, max_length=200, min_length=30, do_sample=False)

    # Use spaCy for keyword extraction
    nlp = spacy.load("en_core_web_sm")
    doc = nlp(text_data)

    # Extract keywords

    # Count word frequencies
    word_freq = Counter([token.text for token in doc if token.is_alpha and not token.is_stop])

    # Set a frequency threshold (e.g., 2)
    min_frequency = 2

    # Extract keywords based on frequency
    keywords = [word for word, freq in word_freq.items() if freq >= min_frequency]
    for i in range(len(keywords) - 1):
        keywords[i] = keywords[i].lower()

    if len(keywords) != 0:
        keywords = mergeSort(keywords, 0, len(keywords) - 1)

    return summary[0]['summary_text'], keywords

if __name__ == "__main__":
    url = "https://learn.lboro.ac.uk"
    summary, keywords = summarise(url)

    print(summary)
    for i in keywords:
        print(i)
