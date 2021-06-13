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
      return "<h2>Untitled Article</h2>";
    } else {
      return "<h2>" . $result["metadata.title"] . "</h2>";
    }
  }

  function get_result_authors($result) {
    if (!isset($result["metadata.authors.last"])) {
      $output = "";
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
    return "<h3>" . $output . "</h3>";
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

  function get_result_document($result) {
    $output = "";
    # title
    if (isset($result["metadata.title"])) {
      $output .= "<h1>" . $result["metadata.title"] . "</h1>";
    }
    # authors
    if (isset($result["metadata.authors.last"])) {
      $output .= "<h2>";
      $author_count = count($result["metadata.authors.last"]);
      for ($i = 0; $i < $author_count; $i++) {
        $output .= $result["metadata.authors.first"][$i] . " " . $result["metadata.authors.last"][$i];
        if ($i < $author_count-2) {
          $output .= ", ";
        } elseif ($i == $author_count-2) {
          $output .= " and ";
        }
      }
      $output .= "</h2>";
    }
    # abstract
    if (isset($result["abstract.text"])) {
      $output .= "<h3>Abstract</h3>";
      foreach ($result["abstract.text"] as $text) {
        $output .= "<p>" . $text . "</p>";
      }
    }
    # body
    if (isset($result["body_text.text"])) {
      $section = "";
      $text_count = count($result["body_text.text"]);
      for ($i = 0; $i < $text_count; $i++) {
        if (isset($result["body_text.section"][$i]) && $result["body_text.section"][$i] != $section) {
          $section = $result["body_text.section"][$i];
          $output .= "<h3>" . $section . "</h3>";
        }
        $output .= "<p>" . $result["body_text.text"][$i] . "</p>";
      }
    }
    return $output;
  }

  function present_search_results($results) {
    if (!isset($results) || !$results) {
      echo "No Results";
    } else {
      $i = 0;
      foreach ($results["response"]["docs"] as $result) {
        echo "
          <div class='result-box' data-result-index='" . $i . "'>
            <a href='javascript:void(0);' onclick='openResult(" . $i . ");'>" . get_result_title($result) . "</a>
            " . get_result_authors($result) . "
            " .  get_result_description($result) . "
          </div>
          <div id='document-" . $i . "' class='document-wrapper' onclick='closeResult(" . $i . ");'>
            <div class='document-box'>
              <img src='media/close-button.png'/>
              " . get_result_document($result) . "
            </div>
          </div>
        ";
        $i++;
      }
    }
  }
?>