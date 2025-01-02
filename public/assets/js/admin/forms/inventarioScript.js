crud.field('componente').onChange(function(field) {
    if (field.value == 0) {
      
        crud.field('componente_id').hide().disable();
        crud.field('idarticulo').show().enable();

    } else {
        crud.field('componente_id').show().enable();
        crud.field('idarticulo').hide().disable();
    }
}).change();