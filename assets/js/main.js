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

const id = element => document.getElementById(element)
const e = element => document.querySelector(element)
const log = (...data) => console.log(...data)

function mask(m,t,e){
    var cursor = t.selectionStart;
    var texto = t.value;
    let thisId
	texto = texto.replace(/\D/g,'');
	var l = texto.length;
	var lm = m.length;
	if(window.event) {                  
	    thisId = e.keyCode;
	} else if(e.which){                 
	    thisId = e.which;
	}
	cursorfixo=false;
	if(cursor < l)cursorfixo=true;
	var livre = false;
	if(thisId == 16 || thisId == 19 || (thisId >= 33 && thisId <= 40))livre = true;
 	ii=0;
 	mm=0;
 	if(!livre){
	 	if(thisId!=8){
		 	t.value="";
		 	j=0;
		 	for(i=0;i<lm;i++){
		 		if(m.substr(i,1)=="#"){
		 			t.value+=texto.substr(j,1);
		 			j++;
		 		}else if(m.substr(i,1)!="#"){
		 			t.value+=m.substr(i,1);
		 		}
		 		if(thisId!=8 && !cursorfixo)cursor++;
		 		if((j)==l+1)break;
		 		
		 	} 	
	 	}
	 	
 	}
 	if(cursorfixo && !livre)cursor--;
 	t.setSelectionRange(cursor, cursor);
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

function getUfs(selectHtmlRef){
    axios.get('https://servicodados.ibge.gov.br/api/v1/localidades/estados?orderBy=nome').then( ({data:ufs}) =>{
        ufs.forEach(uf =>{
            const op = document.createElement('option')
            op.setAttribute('value', uf.sigla)
			op.append(uf.sigla)
            selectHtmlRef.append(op)
        })
    })
}

function getCities(uf, selectHtmlRef){
	selectHtmlRef.innerHTML = ''
	axios.get(`https://servicodados.ibge.gov.br/api/v1/localidades/estados/${uf}/municipios?orderBy=nome`).then(({data:cities}) =>{
		cities.forEach( city => {
			const op = document.createElement('option')
			op.setAttribute('value', city.nome)
			op.append(city.nome)
			selectHtmlRef.append(op)
		})
	})
}

//MUDAR URL, CONFORME MÁQUINA INDIVIDUAL!!
const urlToDataJSON = `http://localhost/etec/php_aulas/projeto_aux_em/assets/js/data/banks_in_brazil.json`;

function getBankNames(selectHtmlRef){
	axios.get(urlToDataJSON).then(({data:banks}) =>{
		banks.forEach((bank, index) =>{
			const op = document.createElement('option')
			op.setAttribute('value', bank.label )
			op.append(bank.label)
			selectHtmlRef.append(op)
		})
	})
}

function getBankCode(bankName){
	axios.get(urlToDataJSON).then( ({data:banks}) =>{
		const b = banks.filter((bank, index) => bankName === bank.label)
		return b[0].value
	})
}