import { ReactNode } from "react";
import { Track } from "./Track";

export type Playlist = {
    id: number;
    name: string;
    ownerId: number;
    trackList: Track[];
};
