import { ReactNode } from "react";

export type Playlist = {
    id: number;
    name: string;
    ownerId: number;
    trackList: {
        id: number;
        title: string;
        author: string;
        link: string;
    }[];
};
