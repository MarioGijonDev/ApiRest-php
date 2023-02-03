
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>API - Test</title>
    <link rel="stylesheet" href="assets/style.css" type="text/css">
</head>
<body>
<div  class="container">
    <h1>Test api - (Resources obtained from waleman's rest api course [github -> waleman])</h1>
    <div class="divbody">
        <h3>Auth - login</h3>
        <code>
           POST  /auth.php
           <br>
           {
               <br>
               "user" :"",  -> REQUIRED
               <br>
               "password": "" -> REQUIRED
               <br>
            }
        
        </code>
    </div>      
    <div class="divbody">   
        <h3>patients</h3>
        <code>
           GET  /patients.php?page=numberPage
           <br>
           GET  /patients.php?id=patientId
        </code>
        <code>
           POST  /patients.php
           <br> 
           {
            <br> 
               "name" : "",               -> REQUIRED
               <br> 
               "dni" : "",                  -> REQUIRED
               <br> 
               "mail":"",                 -> REQUIRED
               <br> 
               "postalCode" :"",             
               <br>  
               "gender" : "",        
               <br>        
               "phone" : "",       
               <br>       
               "birthDate" : "",      
               <br>         
               "token" : ""                 -> REQUIRED        
               <br>       
           }
        </code>
        <code>
           PUT  /patients.php
           <br> 
           {
            <br> 
               "name" : "",               
               <br> 
               "dni" : "",                  
               <br> 
               "mail":"",                 
               <br> 
               "postalCode" :"",             
               <br>  
               "gender" : "",        
               <br>        
               "phone" : "",       
               <br>       
               "birthDate" : "",      
               <br>         
               "token" : "" ,                -> REQUIRED        
               <br>       
               "patientId" : ""   -> REQUIRED
               <br>
           }
        </code>
        <code>
           DELETE  /patients.php
           <br> 
           {   
               <br>    
               "token" : "",                -> REQUIRED        
               <br>       
               "patientId" : ""   -> REQUIRED
               <br>
           }
        </code>
    </div>
</div>
    
</body>
</html>
