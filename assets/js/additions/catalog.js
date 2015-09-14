$(function() {
  $( "#slider" ).slider({    
      range: true,
      min: 1,
      max: 100000,
      values: [ 2500, 50000 ],
      slide: function( event, ui ) {
        //$( "#amount" ).val( "Ksh." + ui.values[ 0 ] + " - Ksh." + ui.values[ 1 ] );
        $( "#pstart" ).val( ui.values[ 0 ]);
        $( "#pend" ).val( ui.values[ 1 ]);
      }
  });
  //alert(JSON.stringify($( "#slider-range" ).slider( "value")));
  //$( "#pstart" ).val( $( "#slider-range" ).slider( "values", 0 ));
  //$( "#pend" ).val( $( "#slider-range" ).slider( "values", 1 ));
  //$( "#amount" ).val( "Ksh." + $( "#slider-range" ).slider( "values", 0 ) + " - Ksh." + $( "#slider-range" ).slider( "values", 1 ) );
});