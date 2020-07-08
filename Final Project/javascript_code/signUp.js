$(document).ready(function(){

    function checkMatching(){
        var psw1=$("#psw1").val();
        var psw2=$("#psw2").val();
        return psw1===psw2;
    }

    function CheckEmailValid(){
        var pattern=/^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        return pattern.test($("#username").val()) && $("#username").val().length<=255;
    }

    function CheckPassword(){
        var pattern=/.*[a-z].*[A-Z0-9].*|.*[A-Z0-9].*[a-z].*/;

        // Check password length
        if($("#psw1").val().length<2){
            return 1;
        }else if($("#psw1").val().length>255){
            return 3;
            // Check password format
        }else if(!pattern.test($("#psw1").val())){
            return 2;
        }else{
            return 0;
        }
    }

    function UsernameCheckDuplicate(){
        $.post("UsernameCheck.php",
            {
                username: $("#username").val()
            },
            function(data, status){
                $("#errorMsg3").text("");
                if(parseInt(data)!=0){
                    $("#errorMsg3").text("Username already used");
                }
            });
    }

    function tryEnable(){
        if(CheckEmailValid() && CheckPassword()==0 && checkMatching() && $("#errorMsg3").val()===""){
            $("#submit").removeAttr("disabled");
        }else{
            $("#submit").attr("disabled","disabled");
        }

    }

    $("#username").change(function(){
        $("#errorMsg3").text("");
        if(!CheckEmailValid()){
            $("#errorMsg3").text("Insert a valid email address");
        }else {
            UsernameCheckDuplicate();
        }
        tryEnable();
    });

    $("#psw1").change(function(){

        switch(CheckPassword()){
            case 1:
                $("#errorMsg").text("Invalid password: enter at least 2 characters");
                break;
            case 2:
                $("#errorMsg").text("Invalid password: enter at least a lowercase character and a uppercase character or digit");
                break;
            case 3:
                $("#errorMsg").text("Invalid password: too many characters");
                break;
            default:
                $("#errorMsg").text("");
                break;
        }

        $("#errorMsg2").text("");
        if(!checkMatching()){
            $("#errorMsg2").text("Passwords do not match");
        }

        tryEnable();
    });

    $("#psw2").change(function(){
        $("#errorMsg2").text("");
        if(!checkMatching()){
            $("#errorMsg2").text("Passwords do not match");
        }
        tryEnable();
    });
});