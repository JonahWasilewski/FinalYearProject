import tkinter as tk
import searchCrawlList
from WebsiteObject import Website

max_depth = 2  # Maximum depth of crawling
max_pages = 10  # Maximum number of pages to crawl

# Function to simulate a search operation
def perform_search():

    results_text.delete("1.0", "end")

    # Get the text in the query entry field
    query = entry.get()

    # Make sure that the query isnt empty
    if query == "":
        results_label.config(text="Query can't be empty!")
    
    results_label.config(text=f"Search Results for: {query}")

    # Get search results by calling the web crawler
    result_title_list = searchCrawlList.search(query)

    results_text.config(state=tk.NORMAL)
    results_text.delete("1.0", tk.END)
    for i in range(len(result_title_list)):
        results_text.insert(tk.END, result_title_list[i].getTitle() + "\n")
    results_text.config(state=tk.DISABLED)

# Create the main window
root = tk.Tk()
root.title("Search Engine")

# Search Input
entry = tk.Entry(root)
entry.pack(pady=10)

# Search Button
search_button = tk.Button(root, text="Search", command=perform_search)
search_button.pack()

# Display search results
results_label = tk.Label(root, text="")
results_label.pack()

results_text = tk.Text(root, wrap=tk.WORD, state=tk.DISABLED)
results_text.pack()

# Run the Tkinter main loop
root.mainloop()
