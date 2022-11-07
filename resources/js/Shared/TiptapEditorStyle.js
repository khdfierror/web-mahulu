import { Mark } from "@tiptap/core";
import { updateMark } from "prosemirror-commands";

export default class CustomStyle extends Mark {
  get name() {
    return "customStyle";
  }

  get defaultOptions() {
    return {
      levels: ["w-full", "border", "rounded", "overflow-hidden"]
    };
  }

  get schema() {
    return {
      attrs: {
        level: {
          default: ""
        }
      },
      parseDOM: [
        {
          tag: "div.custom-style",
          getAttrs(dom) {
            return { level: dom.classList[1] };
          }
        }
      ],
      toDOM: mark => {
        return ["div", { class: `custom-style ${mark.attrs.level}` }, 0];
      }
    };
  }

  commands({ type }) {
    return attrs => updateMark(type, attrs);
  }
}
