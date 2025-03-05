crud.field('persona_ext').onChange(function(field) {
    if (field.value == 1) {
        crud.field('persona_socio_id').hide().disable();
        crud.field('persona_ext_socio_id').show().enable();
    } else {
        crud.field('persona_socio_id').show().enable();
        crud.field('persona_ext_socio_id').hide().disable();
    }
}).change();