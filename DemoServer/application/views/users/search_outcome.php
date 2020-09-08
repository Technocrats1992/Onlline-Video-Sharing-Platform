<body>
<div class="container col-lg-offset-1 col-lg-10" id="frame">
    <?php if(isset($count)) echo '<div class="alert alert-warning text-left">No results found</div>'?>
    <div id="load_data" class="row">
        <?php if(isset($content)) echo $content?>
    </div>
    <div id="load_data_message">
        <?php if(isset($flag) && $flag == "No results") echo '<div class="alert alert-warning text-left">No results found</div>'?>
    </div>
</div>
</body>