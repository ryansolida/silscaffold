<template>
    <div>
        <TextField v-if="field.component_name=='TextField'" :model="model"  @input="$emit('input',$event);"  :field="field" :item="item" :index="index" />
        <SelectField v-if="field.component_name=='SelectField'" :model="model"  @input="$emit('input',$event);" :field="field" :item="item" :index="index" />
        <ImageField v-if="field.component_name=='ImageField'" :model="model"  @input="$emit('input',$event);" :field="field" :item="item" :index="index" />
        <RepeaterField v-if="field.component_name=='RepeaterField'" :model="model"  @input="$emit('input',$event);" :field="field" :item="item" :index="index" />
        <ManySelectField v-if="field.component_name=='ManySelectField'" :model="model" @input="$emit('input',$event);" :field="field" :item="item" :index="index" />
        <TextareaField v-if="field.component_name=='TextareaField'" :model="model" @input="$emit('input',$event);" :field="field" :item="item" :index="index" />
        <WysiwygField v-if="field.component_name=='WysiwygField'" :model="model" @input="$emit('input',$event);" :field="field" :item="item" :index="index" />
    </div>
</template>

<script>
export default {
    props: ['field','item','index','model']
}
</script>