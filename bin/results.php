<?php
  require_once("bin/config.php");

  function get_search_results($query) {
    global $SOLR_URL;
    try {
      $response = file_get_contents($SOLR_URL . urlencode($query));
      return json_decode($response, true);
    } catch (Exception $e) {
      return false;
    }
  }

  function get_result_title($result) {
    if (!isset($result["metadata.title"])) {
      return "No Title";
    } else {
      return $result["metadata.title"];
    }
  }

  function get_result_authors($result) {
    if (!isset($result["metadata.authors.last"])) {
      $output = "No Authors";
    } else {
      $author_count = count($result["metadata.authors.last"]);
      if ($author_count > 3) {
        $output = $result["metadata.authors.last"][0] . " <i>et al.</i>";
      } else {
        $output = "";
        for ($i = 0; $i < $author_count; $i++) {
          $output .= $result["metadata.authors.last"][$i];
          if ($i <= $author_count-3) {
            $output .= ", ";
          } elseif ($i == $author_count-2) {
            $output .= " and ";
          }
        }
      }
    }
    return $output;
  }

  function get_result_description($result) {
    if (!isset($result["abstract.text"]) && !isset($result["body_text.text"])) {
      $output = "<p>No Description</p>";
    } else {
      $content = [];
      if (isset($result["abstract.text"])) {
        $content = $result["abstract.text"];
      }
      if (isset($result["body_text.text"])) {
        $content = array_merge($content, $result["body_text.text"]);
      }
      $output = "<p>";
      $word_count = 0;
      for ($i = 0; $i < count($content); $i++) {
        $words = explode(" ", $content[$i]);
        for ($j = 0; $j < count($words) && $word_count < 50; $j++) {
          $output .= $words[$j] . " ";
          $word_count++;
        }
        if ($word_count >= 50) {
          $output .= "...</p>";
          break;
        }
      }
    }
    return $output;
  }

  function present_search_results($results) {
    if (!$results) {
      echo "Server Unreachable!";
    } else {
      foreach ($results["response"]["docs"] as $result) {
        echo "
          <div class='result-box'>
            <h2>" . get_result_title($result) . "</h2>
            <h3>" . get_result_authors($result) . "</h3>
            " .  get_result_description($result) . "
          </div>
        ";
      }
    }
  }

 # function format_result($)
?>