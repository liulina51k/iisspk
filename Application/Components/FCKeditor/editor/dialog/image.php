<HTML>
<HEAD>
<title>插入图片</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style>
td { font-size:12px; }
input { font-size:12px; }
</style>
<script language=javascript>
var oEditor	= window.parent.InnerDialogLoaded();
var oDOM		= oEditor.FCK.EditorDocument;
var FCK = oEditor.FCK;
var picnum = 1;

function ImageOK()
{
	var inImg,ialign,iurl,imgwidth,imgheight,ialt,isrc,iborder;

	iborder = document.form1.border.value;
	imgwidth = document.form1.imgwidth.value;
	imgheight = document.form1.imgheight.value;
	ialt = document.form1.alt.value;
	
	isrc = document.form1.imgsrc.value;

	
	if(ialign!=0) ialign = " align='"+ialign+"'";
	inImg  = "<img src='"+ isrc +"' width='"+ imgwidth;
	inImg += "' height='"+ imgheight +"' border='"+ iborder +"' alt='"+ ialt +"'"+ialign+"/>";

	if(oImage){
		 var oImage = FCK.Selection.GetSelectedElement() ;
		 oImage.pasteHTML(inImg);
	}else{
		 FCK.InsertHtml(inImg);
	}
    window.close();
}


function UpdateImageInfo()
{
	var imgsrc = document.form1.imgsrc.value;
	if(imgsrc!="")
	{
	  var imgObj = new Image();
	  imgObj.src = imgsrc;
	  document.form1.himgheight.value = imgObj.height;
	  document.form1.himgwidth.value = imgObj.width;
	  document.form1.imgheight.value = imgObj.height;
	  document.form1.imgwidth.value = imgObj.width;
  }
}

function UpImgSizeH()
{
   var ih = document.form1.himgheight.value;
   var iw = document.form1.himgwidth.value;
   var iih = document.form1.imgheight.value;
   var iiw = document.form1.imgwidth.value;
   if(ih!=iih && iih>0 && ih>0 && document.form1.autoresize.checked)
   {
      document.form1.imgwidth.value = Math.ceil(iiw * (iih/ih));
   }
}
function UpImgSizeW()
{
   var ih = document.form1.himgheight.value;
   var iw = document.form1.himgwidth.value;
   var iih = document.form1.imgheight.value;
   var iiw = document.form1.imgwidth.value;
   if(iw!=iiw && iiw>0 && iw>0 && document.form1.autoresize.checked)
   {
      document.form1.imgheight.value = Math.ceil(iih * (iiw/iw));
   }
}
</script>
<link href="base.css" rel="stylesheet" type="text/css" />
<base target="_self" />
</HEAD>
<body bgcolor="#EBF6CD" leftmargin="4" topmargin="2">
<form name="form1" id="form1" method="post">
<input type="hidden" name="himgheight" value="" />
<input type="hidden" name="himgwidth" value="" />
<table width="100%" border="0">
		<tr>
		<td>
			<fieldset>
				<legend>已有图片</legend>
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="65" height="25" align="right">地址：</td>
            <td colspan="2">
              <input name="imgsrc" type="text" id="imgsrc" size="30" value="" />
             </td>
          </tr>
          <tr>
            <td height="25" align="right">宽度：</td>
            <td colspan="2" nowrap>
							<input type="text"  id="imgwidth" name="imgwidth" size="8" value="" onChange="UpImgSizeW()" />
              &nbsp;&nbsp;
              高度: <input name="imgheight" type="text" id="imgheight" size="8" value="" onChange="UpImgSizeH()" />
              <input type="button" name="Submit" value="原始" class="binput" style="width:40" onClick="UpdateImageInfo()" />
              <input name="autoresize" type="checkbox" id="autoresize" value="1" checked='1' />
              自适
            </td>
          </tr>

        
					<tr>
            <td height="25" align="right">
            边框：
            </td>
            <td nowrap='1'>
             <input name="border" type="text" id="border" size="4" value="0" />
              &nbsp;替代文字:
              <input name="alt" type="text" id="alt" size="10" />
            </td>
            <td align="right" nowrap='1'>
            	<input onClick="ImageOK();" type="button" name="Submit2" value=" 确定 " class="binput" />
            </td>
          </tr>
        </table>
      </fieldset>
		</td>
	</tr>
</table>

</form>
</body>
</HTML>
<script language=javascript>
var oImage = FCK.Selection.GetSelectedElement();

if ( oImage && oImage.tagName != 'IMG' && !( oImage.tagName == 'INPUT' && oImage.type == 'image' ) )
	oImage = null ;

if (  oImage ) {	

	var sUrl = oImage.getAttribute( '_fcksavedurl' ) ;
	if ( sUrl == null )
		sUrl = GetAttribute( oImage, 'src', '' ) ;

	document.form1.imgsrc.value    = sUrl ;

	var iWidth, iHeight;

	var regexSize = /^\s*(\d+)px\s*$/i ;
	
	if ( oImage.style.width )
	{
		var aMatchW  = oImage.style.width.match( regexSize ) ;
		if ( aMatchW )
		{
			iWidth = aMatchW[1] ;
			oImage.style.width = '' ;
		}
	}

	if ( oImage.style.height )
	{
		var aMatchH  = oImage.style.height.match( regexSize ) ;
		if ( aMatchH )
		{
			iHeight = aMatchH[1] ;
			oImage.style.height = '' ;
		}
	}
	document.form1.imgwidth.value	= iWidth ? iWidth : oImage.getAttribute('width') ;
	document.form1.imgheight.value = iHeight ? iHeight :oImage.getAttribute('height') ;

	document.form1.alt.value      = oImage.getAttribute('alt') ;
	document.form1.border.valuee	= oImage.getAttribute('border') ;

}
</script>