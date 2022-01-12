window.addEventListener('load', function(){
    const cabecalho = document.querySelector('.container-cabecalho')
    var alturaCabecalho = cabecalho.offsetHeight
    var posicaoScrollAtual = 0
    let gridPrincipal = document.querySelector('.container-principal')
    var btnsExpandeInfosProjeto = document.querySelectorAll('.btn-expande-footer')

    

    //A primeira section do grid principal deve ter um padding-top para descontar a altura do cabecalho

    //let primaryChild =  gridPrincipal.children[0]

    //primaryChild.style.paddingTop = alturaCabecalho+'px'

    document.querySelectorAll('.padding-cabecalho').forEach(e=>{
        e.style.paddingTop = alturaCabecalho+'px'
    })

btnsExpandeInfosProjeto.forEach(btn=>{

    btn.addEventListener('click', function(){
        ExpandeInfosProjeto(btn)
    })

})

//Eventos de scroll
window.addEventListener('scroll', function(){

    
    posicaoScrollAtual = window.scrollY

    voltaTopo(posicaoScrollAtual)

})


//FUNÇÕES

function ExpandeInfosProjeto(btn){
    let infosProjeto = btn.nextElementSibling ? btn.nextElementSibling : false
    let footerProjeto = infosProjeto.parentNode
    let alturaInfosProjeto = 0
    if(infosProjeto){
        alturaInfosProjeto = !!infosProjeto.style.maxHeight
    }
    if(alturaInfosProjeto){
        infosProjeto.style.maxHeight = null
        footerProjeto.classList.remove('footer-projeto-open')
    }else{
        let infosProjetos = document.querySelectorAll('.infos-projeto') //Todos os box infos 
        infosProjetos.forEach(info =>{
            let alturaInfo = info.style.maxHeight
            let footer = info.parentNode
            if(alturaInfo){
                info.style.maxHeight = null
                footer.classList.remove('footer-projeto-open')
            }
        })
        infosProjeto.style.maxHeight = infosProjeto.scrollHeight+'px'
        footerProjeto.classList.add('footer-projeto-open')
    }
}

function voltaTopo(posicaoScrollAtual){

    let btn = document.querySelector('.btn-volta-top')

    if(posicaoScrollAtual > (100/(1/2))){

        btn.classList.add('btn-volta-top-visivel')

        btn.addEventListener('click', function(){

            window.scrollTo({
                top:0 - alturaCabecalho,
                left:0,
                behavior:'smooth'
            })

        })

    }else{
        btn.classList.remove('btn-volta-top-visivel')
    }

}
//Animações que acontce com base no scroll

(function(){
    /* Titulos das sections */
    var headersSections = document.querySelectorAll('.header-section')
    var offset = window.innerHeight * 3 / 4

    function animaTitulo(){
        var docTop = window.scrollY

        headersSections.forEach(function(header, indice){
            var separador = header.querySelector('.divisor-title')
            var subtitulo = header.querySelector('.subtitle')
            var headerTop = header.offsetTop

            if(docTop > headerTop - offset * 1.2){
                header.classList.add('header-active')
            }else{
                header.classList.remove('header-active')
            }
        })
    }

    /* Projetos */
    var projetos = document.querySelectorAll('.projeto')

    function animaProjeto(){
        var docTop = window.scrollY

        projetos.forEach(projeto=>{
            var projetoTop = projeto.offsetTop
            if(docTop > projetoTop - offset * 1){
                projeto.classList.add('projeto-active')
            }else{
                projeto.classList.remove('projeto-active')
            }
        })
    }

    animaTitulo()
    animaProjeto()
    window.addEventListener('scroll', function(){
        animaTitulo()
        animaProjeto()
    })
}())



})