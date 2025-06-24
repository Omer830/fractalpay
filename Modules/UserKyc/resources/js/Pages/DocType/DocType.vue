<template>
    <doc-type :cardData="docsTypesArray" @card-click="cardClickHandler" @header-back="handleBack"/>
</template>

<script>
import { DocType } from "@/components/index.js";
export default {
    components: {
        DocType
    },
    data() {
        return {
            docsTypesArray: [
                {
                    image: {
                        icon: "primary_doc",
                        height: 38,
                        width: 39
                    },
                    title: "Primary Documents",
                    desc:
                        "The 100 Point Identification Check must be lodged with the completed application to the NSW Ministry of Health Central Register.",
                },
                {
                    image: {
                        icon: "secondary_doc",
                        height: 36,
                        width: 37
                    },
                    title: "Secondary Documents",
                    desc:
                        "The 100 Point Identification Check must be lodged with the completed application to the NSW Ministry of Health Central Register.",
                },
                {
                    image: {
                        icon: "other_doc",
                        height: 34,
                        width: 36
                    },
                    title: "Other Documents",
                    desc:
                        "The 100 Point Identification Check must be lodged with the completed application to the NSW Ministry of Health Central Register.",
                },
            ],
        }
    },
    methods: {
        cardClickHandler(card) {
            switch (card.title) {
                case "Primary Documents":
                   this.$inertia.visit('/primary-documents')
                    break;
                case "Secondary Documents":
                this.$inertia.visit('/secondary-documents')
                    break;
                case "Other Documents":
                this.$inertia.visit('/other-documents')
                    break;
                default:
                    break;
            }
        },
        handleBack(){
          this.$inertia.visit('/steps')
        }
    }
}
</script>

<script scoped></script>