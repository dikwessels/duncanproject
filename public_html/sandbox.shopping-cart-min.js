$.bindCheckOutButton=function(){$("#check-out").bind("click",function(){if($("#shipping-zip").val()||giftCardOnly){var e="giftwrap="+$("#gift-wrap").val();e=e+"&note="+encodeURIComponent($("#note").val()),$.get("updateSession.php",e,function(){window.location.href="https://www.asyoulikeitsilvershop.com/checkout.php"})}else alert("Please enter your shipping zip code.\n\rA shipping zip code is required to determine tax and estimate the shipping."),$("#shipping-zip").focus()})},$.bindRemoveButtons=function(){$(".remove-button").each(function(){$(this).bind("click",function(){var e=confirm("Remove this item from your invoice?");if(e){var t=$(this).data("remove");$.removeItem(t)}})})},$.bindResumeShoppingButton=function(){$("#continue-shopping").bind("click",function(){window.location.href="http://www.asyoulikeitsilvershop.com/"})},$.bindUpdateQuantity=function(){$("#update-quantities").bind("click",function(){var e=$(this).data("value").toString();$.updateItems(e,"","self");var t=$("#shipping-method>option:selected").data("method");$.showOrderSummary(t)})},$.bindShippingZip=function(){$("#shipping-zip").on("blur",function(){var e=$(this).val();if(5===e.length){var t=$("#shipping-method>option:selected").data("method"),i="zip="+e+"&smethod="+t;$.post("getsalestax.php","zip="+e,function(e){var t=parseFloat(e);$("#salestax").data("value",t.toFixed(2)),$("#salestax").html("$"+t.toFixed(2)),$("#invoice-tax").val(t.toFixed(2)),$("#temp-total").css({opacity:.5}),$("#button-overlay").fadeIn(),$("#shipping-method-overlay").fadeIn(400,function(){$.post("shippingRatesBeta.php",i,function(e){$("#shipping-method").find("option").remove().end(),$("#shipping-method").html(e),$("#shipping-method-overlay").fadeOut(),$("#shipping-method").fadeIn(),$("#button-overlay").fadeOut(),$.updateOrderTotals(),$("#temp-total").css({opacity:1})})})})}else $("#shipping-zip").attr("placeholder","Please enter a 5-digit zip code."),$("#shipping-zip").focus()})},$.bindOptionalFields=function(){var e="";$("#gift-wrap").bind("change",function(){e=$(this).val(),$.get("updateSession.php","giftwrap="+e,function(e){console.log(e)})}),$("#note").bind("change",function(){e=$(this).val(),$.get("updateSession.php","note="+e,function(e){console.log(e)})})},$.bindPayPalButton=function(){$("#express-submit").bind("click",function(e){e.preventDefault();var t=$("#shipping-method>option:selected").data("method");console.log("local hasPhoneNumber:"+hasPhoneNumber),giftCardOnly?"yes"===hasPhoneNumber?($.getPayPalParams(),console.log("refreshing paypal button"),$("#express-checkout-form").submit()):$("#page-overlay").fadeIn(200,function(){$("#popup").css("display","block"),$("#contact-phone").focus()}):$("#shipping-zip").val()?"yes"===hasPhoneNumber?($.getPayPalParams(),$("#express-checkout-form").submit()):$("#page-overlay").fadeIn(200,function(){$("#popup").css("display","block"),$("#contact-phone").focus()}):(alert("Please enter your shipping zip code.\n\rA shipping zip code is required to determine tax and estimate the shipping."),$("#shipping-zip").focus())})},$.checkForGiftCards=function(){var e=decodeURIComponent(document.cookie),t=e.indexOf("items="),i=!1,n=1,o=0,a="",s=0;if(0>t)i=!1,n=0;else for(e=e.substring(t+6),t=0,o=e.indexOf(";"),1>o&&(o=e.length),e=e.substring(t,o),a=e.split("&"),s=0;s<a.length;s++)""!==a[s]&&("gc"!==a[s].substring(0,2)?n=0:i=!0);return console.log("giftCardOnly = "+n),n},$.checkForSession=function(){var e=0;return $.ajax({type:"get",url:"checkforsession.php",datatype:"json",success:function(t){if("not set"!==t){var i=$.parseJSON(t);i.shippingsurcharge&&(e=i.shippingsurcharge),i.giftwrap&&$("#gift-wrap").val(i.giftWrap),i.note&&$("#note").val(i.note)}}}),e},$.displayOrder=function(e,t){hasPhoneNumber="no",$("#invoice-items").html(e),$.get("checkforsession.php",function(e){if("not set"!==e){var t=$.parseJSON(e);t.shippingsurcharge&&(shipsurcharge=t.shippingsurcharge),t.giftwrap&&$("#gift-wrap").val(t.giftwrap),t.note&&$("#note").val(t.note),t.phone&&""!==t.phone&&(hasPhoneNumber="yes")}}),$.bindOptionalFields(),$.bindPayPalButton(),$.bindCheckOutButton(),$.bindResumeShoppingButton(),$.bindRemoveButtons(),$.bindUpdateQuantity(),t||($("#shipping-method").bind("change",function(){$.updateOrderTotals()}),$.bindShippingZip()),e.indexOf("empty")>0?($("#check-out-button").css("display","none"),$("#express-checkout").css("display","none")):($("#check-out-button").css("display","inline-block"),$("#express-checkout").css("display","inline-block"));var i=$("#shipping-method>option:selected").data("method");$.getPayPalParams(),$.refreshPayPalButton(i)},$.getPayPalParams=function(){$.get("paypalFormInputs.php",function(e){console.log(e)})},$.refreshPayPalButton=function(e){var t="smethod="+e;$.post("createPayPalButton.php",t,function(e){$("#pp-invoice-items").val(e)})},$.removeItem=function(e){var t="id="+e,i="";$.post("https://www.asyoulikeitsilvershop.com/removeitem.php",t,function(){i=$("#shipping-method>option:selected").data("method"),$.showOrderSummary(i)})},$.showOrderSummary=function(e){giftCardOnly=$.checkForGiftCards();var t="giftcardonly="+giftCardOnly;e&&(t=t+"&shipsurcharge="+e),$.post("sandbox.order-details.php",t,function(e){$.displayOrder(e,giftCardOnly)})},$.update=function(e,t,i,n){var o=0,a=0,s="",p=[],r="",c=0,d="",h="";n||(n=$("#quantity"+e).val()),h=decodeURIComponent(document.cookie),o=h.indexOf("items="),0>o?h="":((a=h.indexOf(";",o+6))<1&&(a=h.length),h&&(h=h.substring(h.indexOf("items=")+6,a))),o=h.indexOf(e),-1!==o?(a=h.indexOf("&",o),-1===a&&(a=h.length),s=h.substring(o,a),p=s.split(":"),r=p[1],c=0,p[2]&&(c=p[2]),1>i&&(i=0),0===e||0===e.indexOf("gc",0)?(d=p[2],0===n?h=h.replace("&"+s,""):n>=25?h=h.replace("&"+s,"&"+e+":"+n+":"+d):alert("The minimum amount for a gift card is $25.00\r\n You can remove the gift card by entering 0 for the amount.")):n>0?(t&&(n=parseInt(r)+n),h=h.replace("&"+s,"&"+e+":"+n+":"+c)):h=h.replace("&"+s,"")):n>0&&(h+="&"+e+":"+n+":"+i);var u=new Date;u.setTime(u.getTime()+144e5);var l=encodeURIComponent(h),f="items="+l+"; expires="+u.toGMTString()+"; path=/";document.cookie=f},$.updateEstimatedDelivery=function(e){$.ajax({type:"GET",url:"estDeliveryDate.php",data:"smethod="+e,success:function(e){$("#estimated-delivery").html(e)}})},$.updateItems=function(e,t,i){var n,o="";try{var a=e.split(",");for(n=0;n<a.length;n++)$.update(a[n],0,0)}catch(s){var p=e;$.update(p,0,0)}t?o="orderDetailsPopUp.php":"self"!==i&&(o="sandbox.order-details.php")},$.updateOrderSummary=function(){var e=$("#shipping-method>option:selected").data("method");$.showOrderSummary(e)},$.updateOrderTotals=function(){var e=$("#shipping-method>option:selected").data("method"),t=$("#shipping-method").val(),i="";e||0===e||(e=0,t=4),i="smethod="+e+"&shipsurcharge="+t,$.ajax({type:"POST",datatype:"JSON",url:"sandbox.updateOrderTotal.php",data:i,success:function(t){var i=$.parseJSON(t);$.each(i,function(e,t){var i=parseFloat(t);$("#"+e).is("input")?$("#"+e).val(i.toFixed(2)):($("#"+e).attr("data-value",i.toFixed(2)),$("#"+e).html("$"+i.toFixed(2)))}),$.refreshPayPalButton(e)}})};