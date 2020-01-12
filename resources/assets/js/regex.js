regex = function (type) {
    switch (type) {
        case 'name': return /[^a-zA-Z0-9 .-ñ]/; break;
        // case 'email': return /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/; break;
        // case 'email': return /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?]/; break;
        // case 'email': return /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
        case 'email': return /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
        case 'text': return /[^a-zA-Z0-9 .,#-ñ?!@$%^&~`*()=+"';/\\]/; break;
        case 'alpha': return /[^a-zA-Z ]/; break;
        case 'numeric': return /[^0-9]/; break;
        case 'alphanumeric': return /[^a-zA-Z0-9]/; break;
        case 'password': return /[^a-zA-Z0-9@#$%!]/; break;
        case 'username': return /[^a-zA-Z0-9.-ñ]/; break;
        case 'amount': return /[^0-9.,]/; break;
        case 'dob': return /[^0-9-]/; break;
    }
}