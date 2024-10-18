function sizeWindow(){

    if (screen.width <= 768){  
    //alert ("Pequeña") 
    $('#sidebar, #content').toggleClass('active');
    $('.collapse.in').toggleClass('in');
    $('a[aria-expanded=true]').attr('aria-expanded', 'false');

    }else{   
    //alert ("Grande")
    } 

  }