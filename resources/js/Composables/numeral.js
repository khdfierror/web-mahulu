import numeral from "numeral";

export const n = (value) => {
    return numeral(value).format("0,0.[00]");
};
