import { Film } from "./Film";

export type Favlist = {
    id: number;
    name: string;
    ownerId: number;
    filmList: Film[];
};
