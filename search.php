<?php
  require_once("bin/results.php");
  if (isset($_GET["page"])) {
    $page = intval($_GET["page"]);
    $start = (intval($_GET["page"])-1)*10;
  } else {
    $page = 1;
    $start = 0;
  }
  if (isset($_GET["q"])) {
    $query = $_GET["q"];
    $results = get_search_results($query, $start);
    $result_count = $results["response"]["numFound"];
    $query_time = round($results["responseHeader"]["QTime"]/1000, 2);
  } else {
    header("Location: /");
    exit();
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title><?php if (strlen($query) > 0) { echo $query . " - "; } ?>CORD-19 Search</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/styles.css"/>
  </head>
  <body>
    <div id="search-wrapper">
      <table>
        <tr>
          <td>
            <a href="/">
              <img id="logo" src="media/cord19-logo.png"/>
            </a>
          </td>
          <td>
            <form action="search.php">
              <input id="search-text" type="text" name="q" value="<?php echo $query; ?>" autofocus/><button id="search-button" type="submit"><img src="media/search-icon.png"/></button>
            </form>
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <?php if($result_count > 0) { ?>
            <p><?php echo "Page " . $page .  " of " . $result_count . " results (" . $query_time . " seconds)"; ?></p>
            <?php } ?>
          </td>
        </tr>
      </table>
    </div>
    <div id="results-wrapper">
      <?php 
        if ($result_count > 0) {
          present_search_results($results, $start); 
        } else {
          echo "
          <div id='no-results-message'>
            <h2>No Results</h2>
          </div>
          ";
        }
      ?>
      <div id="results-navigator">
        <table>
          <tr>
            <td>
              <?php if ($page > 1) { ?>
                <a href="./search.php?q=<?php echo $query; ?>&page=<?php echo ($page-1); ?>">< Previous Page</a>
              <?php } ?>
            </td>
            <td>
              <?php 
                if ($result_count > 0) { 
                  echo "Page " . $page . " of " . ceil($result_count / 10);
                }
              ?>
            </td>
            <td>
              <?php if ($start+10 < $result_count) { ?>
                <a href="./search.php?q=<?php echo $query; ?>&page=<?php echo ($page+1); ?>">Next Page ></a>
              <?php } ?>
            </td>
          </tr>
        </table>
      </div>
    </div>
  </body>
  <script type="text/javascript">
    function openResult(i) { document.getElementById("document-" + i).style.display = "block"; }
    function closeResult(i) { document.getElementById("document-" + i).style.display = "none"; }
  </script>
</html>