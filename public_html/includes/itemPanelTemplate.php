<div style="border:solid #aaa;border-width:0px 1px;" class="cell fiveColumns centered">
 
                     <p class="search">
                    <!--image goes here ok--> {{imagecontent}}         
            
                     </p>
                           <h3 class="searchResultsH3" data-version="2.0">
                     <strong>
                      <a title="{{imgTitle}}" class="search" href="{{staturl}}">{{item}} {{monogram}} </a>
                     </strong>
                    </h3>
                    
        <p class="search">
       		<strong class="itemPrice">${{price}}</strong>
       	</p>
        <div class="spacer">
           <strong class="itemQty">In Stock: {{instock}}</strong>
        </div>
<div>
	<strong>                      
<input type="button" data-product="{{id}}" onclick="document.location.href='/addItem.php?id={{id}}&quantity='+this.form.quantity{{id}}.value+'&temp=h'" value="Add to Cart" class="searchResultAddButton">&nbsp;
    </strong>
                     <input type="text" id="quantity{{id}}" name="quantity{{id}}" size="2" value="1" class="staticItemAddQty">
                   </div>
  </div>