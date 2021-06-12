const params = new URLSearchParams(window.location.search);
const query = params.get("q");

function getResults(query) {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     document.getElementById("results-box").innerHTML = this.responseText;
    }
  };
  xhttp.open("GET", SOLR_URL + query, true);
  xhttp.send();
}

getResults(query);