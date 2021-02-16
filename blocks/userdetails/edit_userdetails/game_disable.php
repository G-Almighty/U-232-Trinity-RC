<?php
/**
 * -------   U-232 Codename Trinity   ----------*
 * ---------------------------------------------*
 * --------  @authors U-232 Team  --------------*
 * ---------------------------------------------*
 * -----  @site https://u-232.duckdns.org/  ----*
 * ---------------------------------------------*
 * -----  @copyright 2020 U-232 Team  ----------*
 * ---------------------------------------------*
 * ------------  @version V6  ------------------*
 */
if ($user['game_access'] != 1) {
  $HTMLOUT.= "<div class='cell medium-12'>
  <fieldset class='fieldset'>
  <legend>" . $lang['userdetails_games'] . "</legend>
  <input name='game_access' value='42' type='radio' id='imunity_pos' aria-describedby='imunity_pos'>
  <label for='imunity_pos'>{$lang['userdetails_remove_game_d']}</label>";
  if ($user['game_access'] == 0) {
      $HTMLOUT.= '<p class="help-text" id="imunity_pos">('.$lang['userdetails_unlimited_d'].')<p>';
  } else {
      $HTMLOUT.= "<p class='help-text' id='imunity_pos'>{$lang['userdetails_until']} " . get_date($user['game_access'], 'DATE') . " (" . mkprettytime($user['game_access'] - TIME_NOW) . " {$lang['userdetails_togo']})</p>";
  } 
  $HTMLOUT.= "</fieldset></div>";
}
if ($user['game_access'] == 1) {
  $HTMLOUT.= '<div class="input-group">
      <span class="input-group-label">' . $lang['userdetails_games'] . '</span>
      <select class="input-group-field" name="game_access">
          <option value="0">'.$lang['userdetails_disable_for'].'</option>
          <option value="1">1 '.$lang['userdetails_week'].'</option>
          <option value="2">2 '.$lang['userdetails_weeks'].'</option>
          <option value="4">4 '.$lang['userdetails_weeks'].'</option>
          <option value="8">8 '.$lang['userdetails_weeks'].'</option>
          <option value="90">'.$lang['userdetails_unlimited'].'</option>
      </select>
      <span class="input-group-label">Comment</span>
      <input class="input-group-field" type="text" name="game_disable_pm">
  </div>';
}
