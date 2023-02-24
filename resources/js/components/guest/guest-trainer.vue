<template>
  <div class="max-w-4xl mx-auto">
      <div class="grid grid-cols-12 gap-x-2 gap-y-2 sm:gap-x-4 sm:gap-y-4">
          <a
              :href="'/register'"
              class="col-span-6 cursor-pointer md:col-span-4 transition duration-150 hover:bg-gray-100 border border-gray-150 max-w-xs w-full mx-auto rounded"
              v-for="row in trainers" :key="row.name"
          >
              <div class="w-full pt-3 pb-1">
                  <p class="text-center text-sm sm:text-base text-gray-700" v-text="row.name"></p>
              </div>
              <div class="px-2 pb-3">
                  <div class="flex justify-center py-2">
                      <img
                          v-for="image in row.pieces.split('')"
                          :key="image"
                          :src="'/images/pieces/'+image+'.svg'" :alt="'labchess ' + row.name + ' Image'"
                          class="w-12 mt-1"
                      >
                  </div>
                  <div>
                      <my-progress-bar
                          :percent="(row.finished_positions * 100 / row.total_positions)"
                          :height="13"
                          :color="getPrimary"
                          class="clear-both mt-4 mb-2"
                      ></my-progress-bar>
                  </div>
              </div>
          </a>
      </div>
  </div>
</template>

<script>
import MyProgressBar from "../assets/my-progress-bar";
import percentageMixin from "../assets/mixins/percentageMixin";

export default {
    name: "guest-trainer",
    mixins: [percentageMixin],
    components: {MyProgressBar},
    data(){
        return {
            trainers: [
                {
                    name: "Elementary Mates",
                    pieces: "K",
                    finished_positions: 57,
                    total_positions: 57
                },
                {
                    name: "Pawn",
                    pieces: "P",
                    finished_positions: 50,
                    total_positions: 57
                },
                {
                    name: "Bishop",
                    pieces: "B",
                    finished_positions: 20,
                    total_positions: 57
                },
                {
                    name: "Knight",
                    pieces: "N",
                    finished_positions: 25,
                    total_positions: 57
                },
                {
                    name: "Pieces",
                    pieces: "NB",
                    finished_positions: 12,
                    total_positions: 57
                },
                {
                    name: "Rook & Pawn",
                    pieces: "RP",
                    finished_positions: 35,
                    total_positions: 57
                },
            ]
        }
    }
}
</script>
