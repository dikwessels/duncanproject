<?php
extract($_GET);
readfile("wedRegInvSearch.html");

echo '<input type="hidden" id="regID" name="regID" value="'.$regID.'">
</form>
<div style="width:770px;height:300px;overflow:auto;border-bottom:1px solid #aaaaaa;" id="spanSearchResults"></div>';

?>