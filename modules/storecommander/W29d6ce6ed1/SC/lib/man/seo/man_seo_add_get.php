<?php
if (!defined('STORE_COMMANDER')) { exit; }
?>
<script type="text/javascript" src="<?php echo SC_JQUERY; ?>"></script>
<style>
.meta_title {
font-size: 18px;
font-weight: normal;
font-family: arial,sans-serif;
line-height: 1.2;
color: #1a0dab;
white-space: nowrap;
padding: 0px;
margin: 0px;
}
.url {
font-size: 14px;
color: #006621;
font-style: normal;
white-space: nowrap;
line-height: 16px;
font-family: arial,sans-serif;
}
.meta_desc {
line-height: 1.4;
word-wrap: break-word;
color: #545454;
font-family: arial,sans-serif;
font-size: small;
font-weight: normal;
}

.none {display: none;}
</style>
<h3 class="meta_title">META Title</h3>
<span class="url">http://www.product/url.html</span><br/>
<span class="meta_desc">Meta description, lorem ipsum......</span>

<h3 class="meta_title_none none">META Title</h3>
<span class="url_none none">http://www.product/url.html</span><br/>
<span class="meta_desc_none none">Meta description, lorem ipsum......</span>

<script>
var tempIdList = (parent.prop_tb._manufacturerSeoGrid.getSelectedRowId()!=null?parent.prop_tb._manufacturerSeoGrid.getSelectedRowId():"");
if(tempIdList.search(",")>=0)
{
    var temp = tempIdList.split(",");
    tempIdList=temp[0];
}
if(tempIdList!=undefined && tempIdList!=null && tempIdList!=0 && tempIdList!="")
{
    idxManufacturerSeoTitle=parent.prop_tb._manufacturerSeoGrid.getColIndexById('meta_title');
    idxManufacturerSeoDesc=parent.prop_tb._manufacturerSeoGrid.getColIndexById('meta_description');

    var meta_title = parent.prop_tb._manufacturerSeoGrid.cells(tempIdList,idxManufacturerSeoTitle).getValue();
    var meta_description = parent.prop_tb._manufacturerSeoGrid.cells(tempIdList,idxManufacturerSeoDesc).getValue();
    var url = parent.prop_tb._manufacturerSeoGrid.getUserData(tempIdList,"url");

    if(meta_title!=undefined && meta_title!=null && meta_title!=0 && meta_title!="")
        $(".meta_title").html(meta_title);
    else
        $(".meta_title").html($(".meta_title_none").html());

    if(meta_description!=undefined && meta_description!=null && meta_description!=0 && meta_description!="")
        $(".meta_desc").html(meta_description);
    else
        $(".meta_desc").html($(".meta_desc_none").html());

    if(url!=undefined && url!=null && url!=0 && url!="")
        $(".url").html(url);
    else
        $(".url").html($(".url_none").html());

}
</script>
