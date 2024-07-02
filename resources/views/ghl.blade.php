
 <div > 

   <button onclick="connectAgency()" class="btn btn-primary"> Connect Agency </button>

 </div>

<script>  

 const redirect_url = '{{url("/callback")}}';
 const client_id = '{{env("clientId")}}';
 const scope = 'oauth.write locations.write locations.read';  
 
const config =  {
    "baseUrl": "https://marketplace.gohighlevel.com",
    "clientId": "{{env("clientId")}}",
    "clientSecret": "{{env("clientSecred")}}",
    "redirect_url": "{{url("/callback")}}"
};

 


 const url = "https://marketplace.gohighlevel.com/oauth/chooselocation?response_type=code&redirect_uri=" + redirect_url + "&client_id=" + client_id + "&scope=" + scope  + "";




  function connectAgency(){ 

    const options = {
        requestType: "code",
        redirectUri: config.redirect_url,
        clientId: config.clientId,
        scopes: [
            "saas/location.write",
            "saas/location.read",
            "locations.write",
            "locations.readonly",
            "users.write",
            "users.readonly"
 

        ]
    };
    

    let link = `${config.baseUrl}/oauth/chooselocation?response_type=${options.requestType}&redirect_uri=${options.redirectUri}&client_id=${options.clientId}&scope=${options.scopes.join(' ')}`;
     console.log(link);
    window.open(link, "_blank");
  } 

</script>