<template>
    <div>
        <button @click="exportCategories()" class="btn btn-primary btn-xl mb-2">
            Выгрузить список сообщений
        </button>
        <div v-if="processing" class="alert alert-warning text-center">
            Сообщения выгружаются
            <div class="spinner-border text-success" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
        <div v-else-if="exportFinished" class="alert alert-success text-center">
            Сообщения выгружены <a :href="downloadLink">(скачать)</a>
        </div>
        <h1 class="text-center mb-3">Список сообщений</h1>
        <table
            class="table table-bordered text-center"
            :class="{ updating: updating }"
        >
            <thead>
                <tr>
                    <th class="sort" @click="sort('id')">
                        id <span v-html="getSortArrow('id')"></span>
                    </th>
                    <th class="sort" @click="sort('author')">
                        author <span v-html="getSortArrow('author')"></span>
                    </th>
                    <th class="sort" @click="sort('message')">
                        message <span v-html="getSortArrow('message')"></span>
                    </th>
                    <th class="sort" @click="sort('created_at')">
                        date
                        <span v-html="getSortArrow('created_at')"></span>
                    </th>
                    <th></th>
                </tr>
                <th>
                    <input
                        v-model="filters.id.value"
                        @input="filter()"
                        class="form-control text-center"
                        placeholder="поиск по id"
                    />
                </th>
                <th>
                    <input
                        v-model="filters.author.value"
                        @input="filter()"
                        class="form-control text-center"
                        placeholder=" поиск по автору"
                    />
                </th>
                <th>
                    <input
                        v-model="filters.message.value"
                        @input="filter()"
                        class="form-control text-center"
                        placeholder="поиск по сообщениям"
                    />
                </th>
                <th>
                    <input
                        v-model="filters.created_at.value"
                        @input="filter()"
                        class="form-control text-center"
                        placeholder="поиск по дате"
                    />
                </th>
            </thead>
            <tbody>
                <tr v-for="message in messages" :key="message.id">
                    <td>{{ message.id }}</td>
                    <td>{{ message.author }}</td>
                    <td>{{ message.message }}</td>
                    <td>{{ message.created_at }}</td>
                    <td>
                        <button
                            title="удалить сообщение"
                            class="btn btn-danger btn-sm"
                            @click="deleteMessage(message.id)"
                        >
                            x
                        </button>
                        <button
                            title="изменить сообщение"
                            class="btn btn-primary btn-sm"
                            @click="changeMessage(message.id)"
                        >
                            &#10000;
                        </button>
                    </td>
                </tr>
                <tr>
                    <td
                        v-if="!messages.length"
                        colspan="4"
                        class="text-muted text-center"
                    >
                        {{ textLoad }}
                    </td>
                </tr>
            </tbody>
        </table>
        <button
            :disabled="currentPage == 1"
            class="btn btn-link"
            @click="getMessages(currentPage - 1)"
        >
            назад
        </button>
        <button
            v-for="(link, idx) in links"
            :key="idx"
            class="btn btn-link"
            :class="getCurrentPageClass(link.label)"
            @click="getMessages(link.label)"
            :disabled="link.label == currentPage"
        >
            {{ link.label }}
        </button>
        <button
            class="btn btn-link"
            @click="getMessages(currentPage + 1)"
            :disabled="currentPage == links.length"
        >
            вперед
        </button>
        <select
            v-model="length"
            @change="getMessages()"
            class="form-control messagesPerPage"
        >
            <option v-for="(length, idx) in messagesPerPage" :key="idx">
                {{ length }}
            </option>
        </select>
        <div v-if="changeMessageShow">
            <div class="mb-3">
                <label for="text" class="form-label"
                    >Сообщение пользователя</label
                >
                <textarea
                    type="textarea"
                    class="form-control w-50"
                    v-model="text"
                ></textarea>
            </div>
            <div class="row mb-0">
                <div class="col-md-8 offset-md-4">
                    <button
                        @click="submitChangeMessage()"
                        class="btn btn-primary"
                    >
                        Изменить
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
            processing: false,
            exportFinished: false,
            downloadLink: null,
            MessageId: "",
            messagesPerPage: [2, 3, 10, 50],
            length: 3,
            messages: [],
            links: [],
            currentPage: null,
            updating: false,
            filters: {
                id: {
                    value: null,
                    type: "=",
                },
                author: {
                    value: null,
                    type: "like",
                },
                message: {
                    value: null,
                    type: "like",
                },
                created_at: {
                    value: null,
                    type: "like",
                },
            },
            changeMessageShow: false,
            delay: null,
            sortColumn: {
                column: "id",
                direction: "asc",
            },
            currentDirection: "asc",
            textLoad: "Загрузка",
        };
    },
    computed: {},
    methods: {
        getSortArrow(column) {
            if (column != this.sortColumn.column) {
                return "&udarr;";
            } else {
                return this.currentDirection == "asc" ? "&#8639;" : " &#8642;";
            }
        },
        sort(column) {
            if (column == this.sortColumn.column) {
                this.currentDirection =
                    this.currentDirection == "asc" ? "desc" : "asc";
                this.sortColumn.direction = this.currentDirection;
            } else {
                this.currentDirection = "asc";
                this.sortColumn.direction = this.currentDirection;
                this.sortColumn.column = column;
            }
            this.getMessages();
        },
        getMessages(page = 1) {
            this.updating = true;
            // if (page == this.currentPage) {
            //     return false
            // }
            const newLink = `/admin/showUserMessages?page=${page}`;
            if (this.$route.fullPath != newLink) {
                this.$router.push(newLink);
            }
            const params = {
                page,
                length: this.length,
                filters: this.filters,
                sortColumn: this.sortColumn,
            };

            axios
                .post("/api/admin/showUserMessages", params)
                .then((response) => {
                    this.messages = response.data.data;
                    const links = response.data.links.splice(
                        1,
                        response.data.links.length - 2
                    );
                    this.links = links;
                    this.currentPage = response.data.current_page;
                })
                .finally(() => {
                    this.updating = false;
                    this.textLoad =
                        this.messages == "" ? "Ничего не найдено" : "";
                });
        },
        getCurrentPageClass(page) {
            return page == this.currentPage ? "current-page" : "";
        },
        filter() {
            clearTimeout(this.delay);
            this.delay = setTimeout(() => {
                this.getMessages();
            }, 1000);
        },
        deleteMessage(MessageId) {
            const params = {
                id: MessageId,
            };
            axios.post("/api/admin/deleteMessage", params).then((response) => {
                this.messages = response.data;
            });
        },
        changeMessage(MessageId) {
            this.changeMessageShow = !this.changeMessageShow;
            this.MessageId = MessageId;
            this.text = this.messages.find((el) => el.id == MessageId).message;
        },
        submitChangeMessage() {
            const params = {
                id: this.MessageId,
                text: this.text,
            };
            axios
                .post("/api/admin/changeMessageUser", params)
                .then((response) => {
                    this.messages = response.data;
                    this.changeMessageShow = false;
                });
        },
        exportCategories() {
            this.processing = true;
            axios
                .post("/api/admin/exportMessages")
                .then((response) => {
                    this.downloadLink = response.data;
                })
                .finally(() => {
                    this.processing = false;
                    this.exportFinished = true;
                });
        },
    },
    mounted() {
        const page = this.$route.query.page;
        this.getMessages(page);
    },
};
</script>

<style scoped>
a {
    text-decoration: none;
    color: rgb(27, 119, 39);
}
.current-page {
    color: rgb(42, 104, 42);
    text-decoration: none;
    background-color: rgba(165, 135, 135, 0.582);
}
.messagesPerPage {
    width: 50px;
    float: right;
    display: inline;
}

table.updating tbody {
    position: relative;
    color: rgba(0, 0, 0, 0.404);
}

table.updating a {
    color: rgba(0, 0, 0, 0.404);
}

table.updating tbody:after {
    content: "";
    width: 100%;
    height: 100%;
    top: 0;
    background-color: rgba(2, 2, 2, 0.05);
}
.sort {
    cursor: pointer;
}
</style>
