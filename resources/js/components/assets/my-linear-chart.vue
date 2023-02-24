<template>
    <div ref="myStage" style="height: 200px">
        <v-stage :config="configKonva">
            <v-layer>
                <v-line v-for="(border, index) in borders" :key="'A'+index" :config="border"></v-line>
                <v-text v-for="(text, index) in rowText" :key="'B'+index" :config="text"></v-text>
                <v-text v-for="(text, index) in colText" :key="'C'+index" :config="text"></v-text>
                <v-line :config="line"></v-line>
                <v-circle v-for="(circle, index) in circles" :key="'D'+index" :config="circle"></v-circle>
            </v-layer>
        </v-stage>
    </div>
</template>

<script>
export default {
    name: "my-linear-chart",

    props: ['array', 'names'],

    mounted() {
        new ResizeObserver(this.drawCanvas).observe(this.$refs.myStage);
        this.drawCanvas();
    },

    data(){
        return {
            configKonva: {
                width: 0,
                height: 0,
            },
            circles: [],
            borders: [],
            rowText: [],
            colText: [],

            line: {
                points: [0,0],
                stroke: '#3D96F2',
                strokeWidth: 2,
            }
        }
    },

    methods: {
        /**
         * Draws the canvas on mounted and on div resize
         */
        drawCanvas() {
            // Editable constants.
            const GRAPH_SIZE = 0.87;
            const NUMBER_OF_ROWS_LINES = 5;
            const NUMBER_OF_COLUMNS = this.names.length;

            let maximum = Math.max(...this.array);
            if(maximum > 40) while(maximum % 40 !== 0) ++maximum;
            else maximum = 40;

            // Non-editable constants.
            const START_XP = maximum / 40 * 10;
            const CANVAS_WIDTH = this.$refs.myStage.clientWidth;
            const CANVAS_HEIGHT = this.$refs.myStage.clientHeight;
            const GRAPH_START = (1 - GRAPH_SIZE) / 2;
            const GRAPH_END = GRAPH_SIZE + GRAPH_START;
            const GRAPH_HEIGHT = CANVAS_HEIGHT * GRAPH_SIZE;
            const GRAPH_WIDTH = CANVAS_WIDTH * GRAPH_SIZE;
            const GRAPH_COLUMN_WIDTH = GRAPH_WIDTH / (NUMBER_OF_COLUMNS - 1);
            const GRAPH_BORDER_HEIGHT = GRAPH_HEIGHT / NUMBER_OF_ROWS_LINES;

            this.configKonva.width = CANVAS_WIDTH;
            this.configKonva.height = CANVAS_HEIGHT;

            this.line.points = [];
            this.borders = [];
            this.circles = [];
            this.rowText = [];
            this.colText = [];

            let xPointer = CANVAS_WIDTH * GRAPH_START;
            let days = this.names;
            let index = 0;

            this.array.forEach(xp => {
                let y = GRAPH_HEIGHT - (GRAPH_HEIGHT * (xp / (NUMBER_OF_ROWS_LINES * START_XP)));
                let x = xPointer;
                let circle = {
                    x: x,
                    y: y,
                    radius: 4,
                    fill: 'white',
                    strokeWidth: 2,
                    stroke: '#3D96F2'
                };
                this.line.points.push(x);
                this.line.points.push(y);

                this.colText.push({
                    text: days[index],
                    fill: '#9CA3AF',
                    align: 'center',
                    fontSize: 13,
                    x:  days[index++].length === 2 ?  x - 9 : x - 6,
                    y: CANVAS_HEIGHT - 12
                });

                this.circles.push(circle);
                xPointer += GRAPH_COLUMN_WIDTH;
            });

            let yPointer = GRAPH_HEIGHT;
            for(let borderNumber = 0; borderNumber < NUMBER_OF_ROWS_LINES; borderNumber++){
                this.borders.push({
                    points: [
                        CANVAS_WIDTH * GRAPH_START, yPointer, CANVAS_WIDTH * GRAPH_END, yPointer
                    ],
                    stroke: '#E9E9E9',
                    strokeWidth: 2,
                });

                let text = borderNumber * START_XP;
                if(text === 0) text = ' 0';

                this.rowText.push({
                    text: text,
                    fill: '#9CA3AF',
                    fontSize: 13,
                    x: 0,
                    y: yPointer - 6
                });
                yPointer -= GRAPH_BORDER_HEIGHT;
            }
        }
    }
}
</script>
