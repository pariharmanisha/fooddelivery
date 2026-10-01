
const links = document.querySelectorAll('nav a');

links.forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault(); 

       
        document.querySelector('nav a.active')?.classList.remove('active');

    
        this.classList.add('active');
    });
});

