let d = document, 
    form = d.getElementById('formularioCrearProducto'), 
    submitButton = d.getElementById('submitButton'),
    inputNombre = d.getElementById('productoNombre'),
    inputDetalle = d.getElementById('productoDetalle'),
    inputStock = d.getElementById('productoStock'),
    inputPrecio = d.getElementById('productoPrecio'),
    invalido = d.getElementById('invalido')


form.addEventListener('submit', e => {
    e.preventDefault()
    invalido.innerHTML = ''
    invalido.classList.remove('alert')
    let b1 = false, 
        b2 = false,
        b3 = false,
        b4 = false, 
        e1 = '',
        e2 = '',
        e3 = '',
        e4 = ''
    
    console.log('buenass')
    numeroNegativo(inputNombre)


    try{
    if (cadenaVacia(inputNombre)) {
        if (!tipoNumero(inputNombre)) {
            if (longitudCadena(inputNombre)) {
                b1 = true
            }else{
                e1 += ' -Longitud de nombre corta- '
                b1 = false
            }
        }else{
            e1 += ' -El nombre del producto no puede ser solamente numeros- '
            b1 = false
        }
    }else{
        e1 += ' -Nombre producto Vacio- '
        b1 = false
    }

    if (cadenaVacia(inputDetalle)) {
        if (!tipoNumero(inputDetalle)) {
            b2 = true
        }else{
            e2 = ' -Detalle es puro numero- '
        b2 = false
        }
    }else{
        e2 = ' -Detalle vacio- '
        b2 = false
    }

    if (cadenaVacia(inputStock)) {
            if (tipoNumero(inputStock)) {
                if (!numeroNegativo(inputStock)) {
                    b3 = true
                }else{
                    e3 = '-Stock no puede ser un numero negativo o cero-'
                    b3 = false
                }
            }else{
                e3 = '-Stock no es de tipo numero-'
                b3 = false
            }
    }else{
        e3 = '-Campo stock vacio'
        b3 = false
    }

    if (cadenaVacia(inputPrecio)) {
            if (tipoNumero(inputPrecio)) {
                if (!numeroNegativo(inputPrecio)) {
                    b4 = true
                }else{
                    e4 = '-Precio es un numero negativo-'
                    b4 = false
                }
            }else{
                e4 = '-Precio no es de tipo numero-'
                b4 = false
            }
        }else{
        e4 = '-Campo precio vacio'
        b4 = false
    }



    if (b1 && b2 && b3 && b4) {
        console.log('todo bien')
        form.submit()

    }else{
        insertar = `<small class='alert alert-danger' style='color:red' >${e1}${e2}${e3}${e4}</small>`
        invalido.innerHTML = insertar
        console.log(insertar)
    }
    }catch(e){
        console.log(e);
    }
    console.log('chau')
} )


function validarEmail(email) {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(email.value);
}

function cadenaVacia(input){
    let bandera = false 
    if (input.value !== '') {
        bandera = true
    }
    return bandera 
}

function longitudCadena(input){
    let bandera = false 
    if (input.value.length >= 4) {
        bandera = true
    }
    return bandera
}

function tipoTexto(input){
    let bandera = false 
    if (isText(input.value)) {
        bandera = true
    }
    return bandera 
}

function tipoNumero(input){
    let bandera = false 
    if (!isNaN(input.value)) {
        console.log(input.value + 'soy numero')
        bandera = true
    }
    return bandera 
}

function caracteresRaros(input){
    const re = /[^a-zA-Z0-9 ]/
    return !re.test(input.value)
}

function numeroNegativo(input){
    let bandera = false 
    if (input.value <= 0) {
        bandera = true
    }
    return bandera
}