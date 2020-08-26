function onlyNumber(event){
    const regex = /^[0-9.]+$/
    const theEvent = event || window.event
    let key = theEvent.keyCode || theEvent.which

    key = String.fromCharCode(key)

    if( !regex.test(key) ){
        theEvent.returnValue = false    
        if(theEvent.preventDefault) theEvent.preventDefault()
    }
}