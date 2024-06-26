<?php
namespace Core;

/**
 * Helper and utility functions.
 */
class Helper {
  /**
   * Creates list of menu items.
   *
   * @param array $menu The list of menu items containing the name and the 
   * URL path.
   * @param string $dropdownClass The name of the classes that maybe set 
   * depending on user input.
   * @return string|false Returns the contents of the active output buffer on 
   * success or false on failure.
   */
  public static function buildMenuListItems($menu,$dropdownClass=""){
    ob_start();
    $currentPage = self::currentPage();
    foreach($menu as $key => $val):
      $active = '';
      if(is_array($val)): ?>
        <li class="nav-item dropdown">
          <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?=$key?></a>
          <div class="dropdown-menu <?=$dropdownClass?>">
            <?php foreach($val as $k => $v):
              $active = ($v == $currentPage)? 'active':''; ?>
              <?php if(substr($k,0,9) == 'separator'): ?>
                <div role="separator" class="dropdown-divider"></div>
              <?php else: ?>
                <a class="dropdown-item <?=$active?>" href="<?=$v?>"><?=$k?></a>
              <?php endif; ?>
            <?php endforeach; ?>
          </div>
        </li>
      <?php else:
        $active = ($val == $currentPage)? 'active':''; ?>
        <li class="nav-item"><a class="nav-link <?=$active?>" href="<?=$val?>"><?=$key?></a></li>
      <?php endif; ?>
    <?php endforeach;
    return ob_get_clean();
  }

  /**
   * Prints to console using JavaScript.
   * 
   * @param string $output The information we want to print to console.
   * @param bool $with_script_tags - Determines if we will use script tabs in 
   * our output.  Default value is true.
   */
  public static function cl($output, $with_script_tags = true) {
    if(CONSOLE_LOGGING) {
      $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) . ');';
      if($with_script_tags) $js_code = '<script>' . $js_code . '</script>';
      echo $js_code;
    }
  }

  /**
   * Determines current page based on REQUEST_URI.
   * 
   * @return string $currentPage  The current page.
   */
  public static function currentPage() {
    $currentPage = $_SERVER['REQUEST_URI'];
    if($currentPage == PROOT || $currentPage == PROOT.'home/index') {
      $currentPage = PROOT . 'home';
    }
    return $currentPage;
  }

  /**
   * Performs var_dump of parameter and kills the page.
   * 
   * @param string $data Contains the data we wan to print to the page.
   */
  public static function dnd($data) {
    echo "<pre>";
    var_dump($data);
    echo "<pre>";
    die();
  }

  /**
   * Gets the properties of the given object
   *
   * @param object $object An object instance.
   * @return array An associative array of defined object accessible 
   * non-static properties for the specified object in scope. If a property 
   * have not been assigned a value, it will be returned with a null value.
   */
  public static function getObjectProperties($object) {
    return get_object_vars($object);
  }
}
