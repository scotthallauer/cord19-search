# CORD-19 Search Engine
- Author: Scott Hallauer
- Date: 15 June 2021

## Installation

### CORD-19 Dataset
Download the CORD-19 dataset from https://www.kaggle.com/allen-institute-for-ai/CORD-19-research-challenge.

### Apache SOLR
Install Apache SOLR 8.8.2 in any directory on your computer. Inside that
directory, create a folder named `collection/` and copy all of the files from 
`document_parses/pdf_json/` in the CORD-19 dataset into that folder.

Next, copy the `cord19_configs/` folder included in this submission (under 
`code/solr/`) into the `server/solr/configsets/` directory for SOLR.

Now open a terminal session at the root directory for SOLR and run the following
commands in order:

```
bin/solr start
bin/solr create -c cord19 -d cord19_configs
bin/post -c cord19 collection/00*.json
bin/post -c cord19 collection/01*.json
bin/post -c cord19 collection/02*.json
bin/post -c cord19 collection/03*.json
bin/post -c cord19 collection/04*.json
bin/post -c cord19 collection/05*.json
bin/post -c cord19 collection/06*.json
bin/post -c cord19 collection/07*.json
bin/post -c cord19 collection/08*.json
bin/post -c cord19 collection/09*.json
bin/post -c cord19 collection/0a*.json
bin/post -c cord19 collection/0b*.json
bin/post -c cord19 collection/0c*.json
```

### Web Search Interface
Start web server on your computer with all of the files under `code/web/` placed
in the server's `htdocs/` directory. The web server must support PHP. A recommended
web server to use is XAMPP.

The search interface should now be accessible from http://localhost

## Usage

Enter queries in the search bar and submit to receive a list of matching results
split into pages of 10 results each. You can navigate between pages using the
"< Previous Page" and "Next Page >" buttons at the bottom of the search results.
Futhermore, you can view the full-text document of any particular result without
leaving the result page by clicking the result title. This will open a pop-up 
window with the corresponding document which can be closed to return to the 
result list.