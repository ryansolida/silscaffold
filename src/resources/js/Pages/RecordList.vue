<template>
    <div>
        <div class="w-full flex">
            <div class="w-1/2 text-3xl font-bold mb-4">
                {{scaffold.display_name_plural}}
            </div>
            <div class="w-1/2 pt-2 text-right lg:text-left">
                <inertia-link :href="'/admin/'+slug+'/new'" class="rounded bg-gray-800 text-white px-8 py-2 shadow font-semibold text-sm">Add New</inertia-link>
            </div>
        </div>
        <div v-for="(item, index) in items" class="py-4 px-4 flex" :class="index%2==0?'bg-gray-200':'bg-gray-100'">
            <div class="w-3/4">{{item.name}}</div>
            <div class="w-1/4">
                <inertia-link :href="'/admin/'+slug+'/view/'+item.id" class="rounded bg-gray-800 text-white px-8 py-2 shadow font-semibold text-sm ml-2">View</inertia-link>
                <a href="javascript:;" @click="deleteRecord(item)" class="rounded bg-gray-800 text-white px-8 py-2 shadow font-semibold text-sm ml-2">Delete</a>
                <!--<inertia-link href="/endpoint" method="post" @click="deleteRecord" :data="{ item: item }">Save</inertia-link>-->
            </div>
        </div>
    </div>
</template>
<script>

export default {
    props: ['items','slug','scaffold'],
    methods: {
        deleteRecord: function(item){
            if ( confirm('Are you sure you want to delete this record?') ){
                this.$inertia.post('/admin/'+this.slug+'/delete/'+item.id);
            }
        }
    }
}
</script>