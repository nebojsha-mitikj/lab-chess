<template>
    <div>
        <h1 class="header">Board Display</h1>

        <div class="grid grid-cols-12 gap-y-2">

            <div class="mt-1 sm:mt-5 col-span-12 lg:col-span-3">
                <div class="grid grid-cols-12 gap-y-2">
                    <div class="col-span-6 lg:col-span-12">
                        <p class="mt-5 mb-4">Board theme:</p>
                        <div class="flex mb-6" v-for="theme in boardThemes">
                            <input
                                type="radio"
                                @change="setBoardTheme(theme)"
                                :value="theme.id"
                                v-model="selectedBoardId"
                                class="mt-3 mr-3"
                            >
                            <div class="grid grid-cols-2 h-10 w-20 mr-10 cursor-pointer" @click="setBoardTheme(theme)">
                                <div class="w-full h-full" :style="'background-color: '+theme.light_square"></div>
                                <div class="'w-full h-full" :style="'background-color: '+theme.dark_square"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-6 lg:col-span-12">
                        <p class="mt-5 lg:mt-0 mb-1">Piece theme:</p>
                        <div class="flex" v-for="theme in pieceThemes">
                            <input
                                type="radio"
                                @change="setPieceTheme(theme)"
                                :value="theme.id"
                                v-model="selectedPieceId"
                                class="mt-6 mr-3"
                            >
                            <div
                                :class="'h-16 w-16 mr-10 cursor-pointer piece '+theme.name.toLowerCase()"
                                @click="setPieceTheme(theme)"
                            ></div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="board-size col-span-12 lg:col-span-9 mt-0 lg:mt-10">
                <chess-board
                    ref="chessBoard"
                    :exclude="['engine', 'left-click']"
                    :boardThemes="boardThemes"
                    :pieceThemes="pieceThemes"
                    :userConfiguration="userConfiguration"
                    :position="position"
                    class="mt-0 lg:mt-20"
                >
                </chess-board>
            </div>
        </div>
    </div>
</template>

<script>
import ChessBoard from "../chess/chess-board";
export default {
    name: "display",
    components: {ChessBoard},
    props: ['boardThemes', 'pieceThemes', 'userConfiguration'],

    data(){
        return {
            selectedBoardId: null,
            selectedPieceId: null,
            // Starting position.
            position: {
                position: 'rnbqkbnr/pppppppp/8/8/8/8/PPPPPPPP/RNBQKBNR w KQkq - 0 1'
            }
        }
    },

    mounted(){
        this.setBoardTheme(this.boardThemes.filter((item) => item.id === this.userConfiguration.board_theme_id)[0], false);
        this.setPieceTheme(this.pieceThemes.filter((item) => item.id === this.userConfiguration.piece_theme_id)[0], false);
    },

    methods : {
        /**
         * Change board theme.
         * @param theme
         * @param request
         */
        setBoardTheme(theme, request = true){
            this.selectedBoardId = theme.id;
            this.$refs.chessBoard.setBoardTheme(theme);
            if(request){
                axios.put('/api/settings/updateBoard', {'board_theme_id': theme.id});
            }
        },

        /**
         * Set Piece theme.
         * @param theme
         * @param request
         */
        setPieceTheme(theme, request = true){
            this.selectedPieceId = theme.id;
            this.$refs.chessBoard.removePiecesFromBoard();
            this.$refs.chessBoard.setPieceTheme(theme);
            if(request){
                axios.put('/api/settings/updatePiece', {'piece_theme_id': theme.id});
            }
        }
    }
}
</script>

<style scoped>
    .board-size {
        width: 80%;
        height: 80%;
        margin: 0 auto;
    }
    @media (max-width: 1024px) {
        .board-size {
            width: 100%;
            height: 100%;
            margin: 0 auto;
        }
    }
    .piece {
        background-size: 100% !important;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>
