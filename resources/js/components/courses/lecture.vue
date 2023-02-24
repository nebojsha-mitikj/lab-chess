<template>
    <div style="min-height: 100vh" :class="{
            'bg-gray-100': lectureProp.type === 'exercise' && activeStep <= stepCount ||
            lectureProp.type === 'lesson' && lectureIncludes('chess-exercise')
    }">
        <lecture-header
            :highestReachedStep="highestReachedStep"
            :stepCount="stepCount"
            :lecture="lectureProp"
        ></lecture-header>

        <lecture-content
            v-if="lecture['1'] != null"
            ref="lectureContent"
            :lecture="lecture"
            :lectureProp="lectureProp"
            :activeStep="activeStep"
            :stepCount="stepCount"
            :userConfiguration="userConfiguration"
            :lectureIsFinished="lectureIsFinished"
            :positionFinished="positionFinished"
            :questionAnswer="questionAnswer"
            :sound="sound"
            :userGoalProgress="userGoalProgress"
            @positionIsFinished="positionIsFinished"
            @userMadeMistake="showMistakeAlert"
            @restartPosition="restartPosition"
            @lastMoveReached="lastMoveReached"
            @userAnswer="userAnswer"
        ></lecture-content>

        <lecture-footer
            ref="lectureFooter"
            :activeStep="activeStep"
            :stepCount="stepCount"
            :mistakeData="mistakeData"
            @restartPosition="restartPosition"
            @restartQuestion="restartQuestion"
            @nextStep="nextStep"
            @previousStep="previousStep"
        ></lecture-footer>

    </div>
</template>

<script>

import LectureHeader from "./components/lecture-header";
import LectureFooter from "./components/lecture-footer";
import LectureContent from "./components/lecture-content";
import soundMixin from "../chess/mixins/soundMixin";

export default {
    name: "lesson",

    props: ['course', 'userConfiguration', 'lectureProp'],

    components: {
        LectureContent,
        LectureFooter,
        LectureHeader
    },

    mixins: [soundMixin],

    mounted() {
        this.getLectureDataFromProp();
        this.stepCount = Object.keys(this.lecture).length;
        this.disableContinueButtonIfNeeded();
    },

    data() {
        return {
            positionFinished: false,
            mistakeData: false,
            lectureIsFinished: false,
            successSound: false,
            questionAnswer: null,
            userGoalProgress: null,
            stepCount: 0,
            highestReachedStep: 1,
            activeStep: 1,
            lecture: {}
        }
    },

    methods: {
        /**
         * On User answer.
         * @param {Boolean} answerData
         */
        userAnswer(answerData) {
            if ((this.lecture[this.activeStep]) != null) {
                this.lecture[this.activeStep].questionAnswer = answerData;
                this.questionAnswer = answerData;
            }

            let correctAnswer = this.lectureGetContent('chess-question').content.answer;

            if (correctAnswer === answerData) {
                this.stepFinished().then(res => {
                    if (!this.lectureIsFinished && this.stepCount < this.highestReachedStep) {
                        axios.post('/api/course/lecture/' + this.lectureProp.lecture_step[0].lecture_id).then(result => {
                            this.userGoalProgress = result.data;
                            this.lectureIsFinished = true;
                        });
                    }
                });
            } else {
                this.makeSound('wrong');
                this.mistakeData = {
                    message: 'Incorrect, try again!'
                };
                this.lecture[this.activeStep].mistakeData = this.mistakeData;
            }
        },

        /**
         * Restart Question.
         */
        restartQuestion() {
            this.lecture[this.activeStep].questionAnswer = null;
            this.lecture[this.activeStep].mistakeData = false;
            this.questionAnswer = null;
            this.mistakeData = false;
        },

        /**
         * Check if active lecture contains content with type :key
         */
        lectureIncludes(key) {
            if (this.lecture[this.activeStep] == null) return false;
            for (let i = 0; i < this.lecture[this.activeStep].body.length; i++)
                if (this.lecture[this.activeStep].body[i].type.includes(key))
                    return true;
            return false;
        },

        /**
         * Get content with type :key
         */
        lectureGetContent(key) {
            if (this.lecture[this.activeStep] == null) return false;
            for (let i = 0; i < this.lecture[this.activeStep].body.length; i++)
                if (this.lecture[this.activeStep].body[i].type.includes(key))
                    return this.lecture[this.activeStep].body[i];
            return false;
        },

        /**
         * Last Move Reached.
         */
        lastMoveReached() {
            if (this.highestReachedStep <= this.activeStep && this.lectureIncludes('chess-lesson')) {
                this.stepFinished();
                if (!this.lectureIsFinished && this.stepCount < this.highestReachedStep) {
                    axios.post('/api/course/lecture/' + this.lectureProp.lecture_step[0].lecture_id).then(result => {
                        this.userGoalProgress = result.data;
                        this.lectureIsFinished = true;
                    });
                }
            }
        },

        /**
         * Restart chess position.
         */
        restartPosition(source = 'footer') {
            this.mistakeData = false;
            this.positionFinished = false;
            this.lecture[this.activeStep].mistakeData = false;
            this.lecture[this.activeStep].positionFinished = false;
            if (source !== 'content') this.$refs.lectureContent.restartPosition();
        },

        /**
         * Shows mistake alert message.
         * @param {Object} mistakeData
         */
        showMistakeAlert(mistakeData) {
            this.mistakeData = mistakeData;
            this.positionFinished = true;
            this.lecture[this.activeStep].mistakeData = mistakeData;
            this.lecture[this.activeStep].positionFinished = true;
            this.makeSound('wrong');
        },

        /**
         * On step finish.
         */
        stepFinished() {
            ++this.highestReachedStep;
            this.$refs.lectureFooter.setDisableContinue(false);
            return this.makeSound('success');
        },

        /**
         * Chess position is finished.
         */
        positionIsFinished() {
            if ((this.lecture[this.activeStep]) != null) {
                this.lecture[this.activeStep].positionFinished = true;
                this.positionFinished = true;
            }
            this.stepFinished().then(res => {
                if (!this.lectureIsFinished && this.stepCount < this.highestReachedStep) {
                    axios.post('/api/course/lecture/' + this.lectureProp.lecture_step[0].lecture_id).then(result => {
                        this.userGoalProgress = result.data;
                        this.lectureIsFinished = true;
                    });
                }
            });
        },

        /**
         * Go to previous step
         */
        previousStep() {
            this.getChessState();
            --this.activeStep;
            this.setDataBasedOnNewPosition();
            this.disableContinueButtonIfNeeded();
        },

        /**
         * Go to next step.
         */
        nextStep() {
            this.getChessState();
            ++this.activeStep;
            this.setDataBasedOnNewPosition();
            if (this.activeStep > this.highestReachedStep) this.highestReachedStep = this.activeStep;
            this.disableContinueButtonIfNeeded();
            if (!this.successSound && this.stepCount < this.activeStep) {
                this.successSound = true;
                this.makeSound('goalReached');
            }
        },

        /**
         * Disable/Enable continue button.
         */
        disableContinueButtonIfNeeded() {
            let buttonShouldBeDisabled = false;

            if (this.highestReachedStep <= this.stepCount && this.highestReachedStep <= this.activeStep) {
                buttonShouldBeDisabled = this.lectureIncludes('chess-lesson') ||
                    this.lectureIncludes('chess-exercise') || this.lectureIncludes('chess-question');
            }

            this.$refs.lectureFooter.setDisableContinue(buttonShouldBeDisabled);
        },

        /**
         * Get last chess state.
         */
        getChessState() {
            let chessState = this.$refs.lectureContent.getChessState();
            if (this.lecture[this.activeStep] != null && this.lecture[this.activeStep].body != null) {
                for (let i = 0; i < this.lecture[this.activeStep].body.length; i++) {
                    if (this.lecture[this.activeStep].body[i].type.includes('chess')) {
                        this.lecture[this.activeStep].body[i].content.chessState = chessState;
                        break;
                    }
                }
            }
        },

        /**
         * Sets data based on new position.
         * @param {Array} keys
         */
        setDataBasedOnNewPosition(keys = ['positionFinished', 'mistakeData', 'questionAnswer']) {
            for (let i = 0; i < keys.length; i++) {
                let key = keys[i];
                this[key] = key === 'questionAnswer' ? null : false;
                if (this.lecture[this.activeStep] != null && this.lecture[this.activeStep][key] != null) {
                    this[key] = this.lecture[this.activeStep][key];
                }
            }
        },

        /**
         * Get lecture data from lectureProp.
         */
        getLectureDataFromProp() {
            for (let i = 0; i < this.lectureProp.lecture_step.length; i++) {
                let lectureStep = this.lectureProp.lecture_step[i];
                this.lecture[lectureStep.number] = {id: lectureStep.number, title: lectureStep.title, body: []};
                let lectureContent = JSON.parse(lectureStep.content);
                for (let j = 0; j < lectureContent.length; j++) {
                    let lectureBody = lectureContent[j];
                    this.lecture[lectureStep.number].body.push({
                        id: lectureBody.number,
                        number: lectureBody.number,
                        type: lectureBody.type,
                        content: lectureBody.content
                    });
                }
                this.lecture[lectureStep.number].body.sort((a,b) => (a.number > b.number) ? 1 : -1);
            }
        },
    }
}
</script>
