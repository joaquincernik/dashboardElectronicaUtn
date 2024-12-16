crud.field('estudiante').onChange(function(field) {
    if (field.value == 0) {
      
        crud.field('facultad').hide().disable();

    } else {
        crud.field('facultad').show().enable();
    }
}).change();