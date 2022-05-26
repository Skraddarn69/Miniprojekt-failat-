function appendClasses(userType) {
    if(userType===1) {
        fetch('../php/getClasses.php')
        .then(function(response) {
            if(response.status == 200) {
                return response.json();
            }
        })
        
    } 
}