<template>
    <div>
        <h1 class="mb-3">Сообщения от пользователей</h1>
        <div
            v-for="message in laravelData.data"
            :key="message.id"
            class="alert alert-success text-center mb-2 col-md-8"
            role="alert"
        >
            <h4 class="alert-heading">
                {{ message.author }}
            </h4>
            <p>
                {{ message.created_at }}
            </p>
            <hr />
            <p class="mb-0">
                {{ message.message }}
                <button
                    v-if="regUserName"
                    class="btn btn-danger btn-sm"
                    @click="deleteMessage(message.id)"
                >
                    x
                </button>
                <button
                    v-if="regUserName"
                    class="btn btn-primary btn-sm"
                    @click="changeMessage(message.id)"
                >
                    &#10000;
                </button>
            </p>
        </div>
        <pagination
            :data="laravelData"
            @pagination-change-page="getResults"
            align="center"
        >
            <span slot="prev-nav">&lt; </span>
            <span slot="next-nav"> &gt;</span>
        </pagination>

        <div class="form-control w-50" v-if="newMessage">
            <div class="mb-3" v-if="!regUserName">
                <label for="name" class="form-label"> Имя </label>
                <input type="text" class="form-control w-50" v-model="name" />
            </div>
            <div class="mb-3">
                <label for="text" class="form-label"> Ваше сообщение</label>
                <textarea
                    type="textarea"
                    class="form-control w-50"
                    v-model="newText"
                ></textarea>
            </div>
            <div class="row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button
                        :disabled="!newText.length || updating"
                        @click="submitMessage()"
                        class="btn btn-primary"
                    >
                        Отправить
                    </button>
                </div>
            </div>
        </div>
        <div v-else class="form-control w-50">
            <div class="mb-3">
                <label for="text" class="form-label"> Ваше сообщение</label>
                <textarea
                    type="textarea"
                    class="form-control w-50"
                    v-model="changeText"
                ></textarea>
            </div>
            <div class="row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button
                        :disabled="!changeText.length || updating"
                        @click="submitChangeMessage()"
                        class="btn btn-primary"
                    >
                        Изменить
                    </button>
                    <button @click="newMessage = true" class="btn btn-primary">
                        Отмена
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            laravelData: {},
            updating: false,
            newMessage: true,
            newText: "",
            changeText: "",
            name: "",
            MessageId: "",
        };
    },
    computed: {
        regUserName() {
            return this.$store.state.user.name;
        },
    },
    methods: {
        submitMessage() {
            this.updating = true;
            this.submitName = this.regUserName ? this.regUserName : this.name;
            const params = {
                name: this.submitName,
                text: this.newText,
            };
            axios
                .post("/api/messages/submitMessage", params)
                .then((response) => {
                    this.laravelData = response.data;
                })
                .finally(() => {
                    this.updating = false;
                });
        },
        deleteMessage(MessageId) {
            const params = {
                id: MessageId,
            };
            axios
                .post("/api/messages/deleteMessage?page=last_page", params)
                .then((response) => {
                    this.laravelData = response.data;
                })
                .finally(() => {
                    this.updating = false;
                });
        },
        changeMessage(MessageId) {
            this.newMessage = false;
            this.MessageId = MessageId;
            this.changeText = this.laravelData.data.find(
                (el) => el.id == MessageId
            ).message;
        },
        submitChangeMessage() {
            this.updating = true;
            const params = {
                id: this.MessageId,
                text: this.changeText,
            };
            axios
                .post("/api/messages/changeMessage", params)
                .then((response) => {
                    this.laravelData = response.data;
                })
                .finally(() => {
                    this.updating = false;
                });
        },
        getResults(page = 1) {
            axios
                .get("/api/messages/get?page=" + page)
                .then((response) => {
                    this.laravelData = response.data;
                })
                .finally(() => {
                    this.updating = false;
                });
        },
    },
    mounted() {
        this.getResults();
        if (this.name === "") {
            this.name = "Гость" + Math.floor(Math.random(5) * (10000 + 1 - 1));
        }
    },
};
</script>
<style scoped>
h1 {
    text-align: center;
}
.alert-heading {
    color: rgb(117, 117, 33);
}
</style>
