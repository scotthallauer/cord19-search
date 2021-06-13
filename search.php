<?php
  require_once("bin/results.php");

  $results = get_search_results($_GET["q"]);
?>
<!DOCTYPE html>
<html>
  <head>
    <title>CORD-19 Search</title>
    <link type="text/css" rel="stylesheet" href="css/reset.css"/>
    <link type="text/css" rel="stylesheet" href="css/styles.css"/>
  </head>
  <body>
    <div id="search-wrapper">
      <table>
        <tr>
          <td>
            <img src="media/cord19-logo.png" width="150px"/>
          </td>
          <td>
            <form action="search.php">
              <input type="text" name="q" value="<?php echo $_GET["q"]; ?>"/>
              <input type="submit" value="Search"/>
            </form>
          </td>
        </tr>
        <tr>
          <td></td>
          <td>
            <p><?php echo $results["response"]["numFound"] . " results (" . round($results["responseHeader"]["QTime"]/1000, 2) . " seconds)"; ?></p>
          </td>
        </tr>
      </table>
    </div>
    <div id="results-wrapper">
      <?php 
        present_search_results($results);
      ?>
    </div>
  </body>
</html>