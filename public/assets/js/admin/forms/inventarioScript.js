crud.field('componente').onChange(function(field) {
    if (field.value == 0) {
      
        crud.field('modelo').hide().disable();

    } else {
        crud.field('modelo').show().enable();
    }
}).change();