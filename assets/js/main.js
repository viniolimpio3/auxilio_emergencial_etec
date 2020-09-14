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

function hide(elements){

    Object.values(elements).forEach( element =>{
        element.setAttribute('hidden','')
    } )
}
function show(elements){
    
    Object.values(elements).forEach(element =>{
        element.removeAttribute('hidden')
    })
}

function setReadOnlyInputs(inputs){
    Object.values(inputs).forEach(field =>{
        field.setAttribute('readonly','')
    })
}
function unsetReadOnlyInputs(inputs){
    Object.values(inputs).forEach(field =>{
        field.removeAttribute('readonly')
    })
}